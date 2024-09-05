<?php

namespace app\index\model\command;

use app\common\model\backend\Channel;
use app\common\model\backend\Platform;
use app\common\tool\traits\Time;
use app\common\tool\redis\operation\OperationData as redis;
use app\common\model\backend\DailyStatistics;
use app\index\model\command\operation\NewUser;
use app\index\model\command\operation\Retention;

class OperationData
{
    use Time;

    /**
     * 线上时间
     * @var
     */
    protected $onlineTime;

    /**
     * redis
     * @var redis
     */
    protected $redis;

    /**
     * 最后一次执行任务时间
     * @var
     */
    protected $lastExecuteTime;

    /**
     * 当前执行任务时间
     * @var int
     */
    protected $currentTime;

    /**
     * 当前零点时间
     * @var
     */
    protected $currentDailyTime;

    /**
     * 平台渠道
     * @var
     */
    protected $pc;

    /**
     * 渠道信息
     * @var
     */
    protected $channel;

    /**
     * 留存率、ltv等最大计算天数
     * @var int
     */
    protected $maxDay = 30;

    /**
     * 日志
     * @var array
     */
    protected $logs = [];

    public function __construct()
    {
        $this->onlineTime = strtotime(config('app.online_date'));
        $this->redis = new redis();
        $this->lastExecuteTime = $this->getLastExecuteTime();
        $this->currentTime = time();
        $this->currentDailyTime = $this->getStartUnixTimestamp($this->currentTime);
        $this->setPcAndChannel();
    }

    /**
     * 获取最后一次执行时间
     * 默认：上线日期
     * @return bool|false|int|mixed|string
     */
    protected function getLastExecuteTime()
    {
        return $this->redis->getLastExecuteTime() ?: $this->onlineTime;
    }

    /**
     * 设置pc和渠道信息
     * 存储新平台和新渠道
     */
    protected function setPcAndChannel()
    {
        $platform = Platform::getAllPlatform();
        $this->channel = Channel::getAllChannelForTask();

        $newPlatform = [];
        $newChannel = [];

        $this->pc = $this->redis->getPc();
        foreach ($this->pc as $pc) {
            list($pId, $channel) = $pc;
            if (!isset($platform[$pId])) {
                $newPlatform[] = [
                    'id' => $pId,
                    'platform_name' => '未知平台，请修改名称',
                ];
            }

            if (!isset($this->channel[$channel])) {
                $newChannel[] = [
                    'channel_name' => $channel,
                ];
            }
        }

        if (!empty($newPlatform)) {
            try {
                // 带主键写入
                (new Platform())->saveAll($newPlatform, false);
            } catch (\Exception $e) {
                $this->log('存储平台失败');
            }
        }

        if (!empty($newChannel)) {
            try {
                // 带主键写入
                $collection = (new Channel())->saveAll($newChannel);

                // 更新channel
                foreach ($collection as $model) {
                    $this->channel[$model->channel_name] = $model->id;
                }
            } catch (\Exception $e) {
                $this->log('存储渠道失败');
            }
        }
    }

    /**
     * 记录日志
     * @param $msg
     */
    protected function log($msg)
    {
        $this->logs[] = $msg;
    }

    /**
     * 记录日志
     */
    public function __destruct()
    {
        $runTime = (microtime(true) - $this->currentTime);
        $msg = "运行时间：{$runTime}s，内存峰值：" . (memory_get_peak_usage(true) / 1024 / 1024) . 'MB';
        $this->log($msg);

        trace($this->logs);
    }

    /**
     * 执行任务
     */
    public function execute()
    {
        // 跨天
        if ($this->isCrossDay()) {
            $this->dealCrossDay();
        }

        $this->newUser($this->currentDailyTime);
        $this->retention($this->currentDailyTime);
        $this->redis->setLastExecuteTimestamp($this->currentTime);
    }

    /**
     * 是否跨天
     * @return bool
     */
    public function isCrossDay()
    {
        return date('Y-m-d', $this->lastExecuteTime) != date('Y-m-d', $this->currentTime);
    }

    /**
     * 处理跨天数据
     */
    protected function dealCrossDay()
    {
        for ($i = $this->lastExecuteTime; $i < $this->currentDailyTime;) {
            $dailyTime = $this->getStartUnixTimestamp($i);
            $this->newUser($dailyTime);
            $this->retention($dailyTime);

            // 保存30天前的统计数据&清理redis缓存
            $maxDayAgoTime = strtotime(sprintf('%s -%d day', date('Y-m-d', $dailyTime), $this->maxDay));
            if ($maxDayAgoTime >= $this->onlineTime) {
                $this->saveStatisticsData($maxDayAgoTime);
            }

            $i = strtotime(sprintf('%s +1 day', date('Y-m-d H:i:s', $i)));
        }
    }

    /**
     * 计算新增用户
     * @param $dailyTime
     */
    protected function newUser($dailyTime)
    {
        $result = (new NewUser($this, $dailyTime))->execute();
        if (!$result) {
            $this->log('执行新增用户任务失败-' . $dailyTime);
        }
    }

    /**
     * 计算留存率
     * @param $dailyTime
     */
    protected function retention($dailyTime)
    {
        $result = (new Retention($this, $dailyTime))->execute();
        if (!$result) {
            $this->log('执行留存率任务失败-' . $dailyTime);
        }
    }

    /**
     * 保存统计数据
     * @param $dailyTime
     */
    protected function saveStatisticsData($dailyTime)
    {
        $data = [];
        foreach ($this->pc as $pc) {
            list($pId, $channel) = $pc;
            $cId = $this->getCId($channel);

            $data[] = [
                'platform' => $pId,
                'channel' => $cId,
                'new_user' => $this->redis->getNewUser($dailyTime, $pId, $channel),
                'active_user' => $this->redis->getActiveUser($dailyTime, $pId, $channel),
                'bankruptcy_user' => $this->redis->getBankruptcyUser($dailyTime, $pId, $channel),
                'daily_time' => $dailyTime,
            ];
        }

        try {
            (new DailyStatistics())->saveAll($data);
            $this->redis->deleteStatisticsData($dailyTime, $this->pc);
        } catch (\Exception $e) {
            $this->log('存储统计数据失败' . $dailyTime);
        }
    }

    /**
     * 获取平台渠道
     * @return mixed
     */
    public function getPc()
    {
        return $this->pc;
    }

    /**
     * 获取渠道id
     * @param $channel
     * @return mixed
     */
    public function getCId($channel)
    {
        return $this->channel[$channel];
    }

    /**
     * 获取线上时间
     * @return false|int
     */
    public function getOnlineTime()
    {
        return $this->onlineTime;
    }

    /**
     * 获取最大天数
     * @return int
     */
    public function getMaxDay()
    {
        return $this->maxDay;
    }

    /**
     * 根据零点时间戳、平台ID、渠道名获取新增用户数量
     * @param $dailyTime
     * @param $pid
     * @param $channel
     * @return int
     */
    public function getNewUserNum($dailyTime, $pid, $channel)
    {
        return $this->redis->getNewUserNum($dailyTime, $pid, $channel);
    }

    /**
     * 根据零点时间戳（计算日、指定日）、平台ID、渠道名获取新增用户留存数量
     * 计算日==指定日，则计算的是计算日留存，即：计算日新增人数
     * @param $dailyTime
     * @param $assignTime
     * @param $pId
     * @param $channel
     * @return int
     */
    public function getNewUserRetention($dailyTime, $assignTime, $pId, $channel)
    {
        if ($dailyTime == $assignTime) {
            $count = $this->redis->getNewUserNum($dailyTime, $pId, $channel);
        } else {
            $count = $this->redis->getNewUserRetention($dailyTime, $assignTime, $pId, $channel);
        }

        return $count;
    }

    /**
     * 根据零点时间戳（计算日、指定日）、平台ID、渠道名获取活跃用户留存数量
     * @param $dailyTime
     * @param $assignTime
     * @param $pId
     * @param $channel
     * @return int
     */
    public function getActiveUserRetention($dailyTime, $assignTime, $pId, $channel)
    {
        return $this->redis->getActiveUserRetention($dailyTime, $assignTime, $pId, $channel);
    }

    /**
     * 根据零点时间戳（计算日、指定日）、平台ID、渠道名获取破产用户留存数量
     * @param $dailyTime
     * @param $assignTime
     * @param $pId
     * @param $channel
     * @return int
     */
    public function getBankruptcyUserRetention($dailyTime, $assignTime, $pId, $channel)
    {
        return $this->redis->getBankruptcyUserRetention($dailyTime, $assignTime, $pId, $channel);
    }
}