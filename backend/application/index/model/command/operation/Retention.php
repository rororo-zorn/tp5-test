<?php

namespace app\index\model\command\operation;

use app\index\model\command\OperationData;
use DateTimeImmutable;
use app\common\model\backend\operation\Retention as model;

class Retention
{
    /**
     * @var OperationData
     */
    protected $operationData;

    /**
     * 当前计算日的零点时间戳
     * @var
     */
    protected $dailyTime;

    /**
     * @var
     */
    protected $dailyTimeObj;

    /**
     * 入库数据
     * @var array
     */
    protected $data = [];

    /**
     * db数据
     * @var array
     */
    protected $dbData;

    /**
     * 查询时间
     * @var array
     */
    protected $queryTime = [];

    /**
     * 留存率类型
     * 1：新增 3.活跃 5.破产
     * @var int[]
     */
    protected $retentionType = [1, 3, 5];

    public function __construct(OperationData $operationData, $dailyTime)
    {
        $this->operationData = $operationData;
        $this->dailyTime = $dailyTime;
        $this->dailyTimeObj = new DateTimeImmutable(date('Y-m-d', $dailyTime));
        $this->dbData = (new model())->getDataByDailyTime($this->getQueryTime());
    }

    /**
     * 获取查询时间
     * 使用strtotime('-n day')方法获取前n天的时间戳，解决PST时区增加和延后时间问题
     * @return array
     */
    protected function getQueryTime()
    {
        if (empty($this->queryTime)) {
            $day = 0;
            $maxDay = $this->operationData->getMaxDay();
            $onlineTime = $this->operationData->getOnlineTime();
            for ($i = $this->dailyTime; $i >= $onlineTime && $day < $maxDay;) {
                $this->queryTime[] = $i;
                $day++;
                $i = strtotime(sprintf('%s -1 day', date('Y-m-d', $i)));
            }
        }

        return $this->queryTime;
    }

    /**
     * 执行任务
     * @return bool
     */
    public function execute()
    {
        $this->setData();

        try {
            if ((new model())->saveAll($this->data)) {
                return true;
            }
            throw new \Exception();
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * 设置入库数据
     * 补齐当天中途新增的类型、平台渠道数据
     */
    protected function setData()
    {
        foreach ($this->retentionType as $type) {
            foreach ($this->queryTime as $dailyTime) {
                foreach ($this->operationData->getPc() as $pc) {
                    list($pId, $channel) = $pc;
                    $cId = $this->operationData->getCId($channel);

                    $find = false;
                    foreach ($this->dbData as $dbData) {
                        if ($dbData['retention_type'] == $type && $dbData['platform'] == $pId && $dbData['channel'] = $cId && $dbData['daily_time'] == $dailyTime) {
                            $this->update($dbData['id'], $type, $dailyTime, $pId, $channel);
                            $find = true;

                            // 删除已找到的项?
                            break;
                        }
                    }

                    // 增加当天未找到的数据
                    if ($dailyTime == $this->dailyTime && !$find) {
                        $this->add($type, $dailyTime, $pId, $cId, $channel);
                    }
                }
            }
        }
    }

    /**
     * 更新
     * @param $id
     * @param $type
     * @param $assignTime
     * @param $pId
     * @param $channel
     */
    protected function update($id, $type, $assignTime, $pId, $channel)
    {
        $field = $this->getRetentionField($assignTime);
        $count = $this->getCount($type, $assignTime, $pId, $channel);

        $data = [
            'id' => $id,
            $field => $count,
        ];

        // 更新当天对应留存人数
        if ($assignTime == $this->dailyTime) {
            $data['count'] = $count;
        }

        $this->data[] = $data;
    }

    /**
     * 获取留存字段
     * @param $dailyTime
     * @return string
     */
    protected function getRetentionField($dailyTime)
    {
        $diff = $this->dailyTimeObj->diff(new DateTimeImmutable(date('Y-m-d', $dailyTime)));
        $index = $diff->d + 1;
        return model::RETENTION_MARK . $index;
    }

    /**
     * 新增（当天）
     * @param $type
     * @param $dailyTime
     * @param $pId
     * @param $cId
     * @param $channel
     */
    protected function add($type, $dailyTime, $pId, $cId, $channel)
    {
        $field = $this->getRetentionField($dailyTime);
        $count = $this->getCount($type, $dailyTime, $pId, $channel);

        $this->data[] = [
            'retention_type' => $type,
            'platform' => $pId,
            'channel' => $cId,
            'count' => $count,
            $field => $count,    // retention1
            'daily_time' => $dailyTime,
        ];
    }

    /**
     * 获取留存数
     * @param $type
     * @param $assignTime
     * @param $pId
     * @param $channel
     * @return int
     */
    protected function getCount($type, $assignTime, $pId, $channel)
    {
        $count = 0;
        switch ($type) {
            case 1:
                $count = $this->operationData->getNewUserRetention($this->dailyTime, $assignTime, $pId, $channel);
                break;
            case 3:
                $count = $this->operationData->getActiveUserRetention($this->dailyTime, $assignTime, $pId, $channel);
                break;
            case 5:
                $count = $this->operationData->getBankruptcyUserRetention($this->dailyTime, $assignTime, $pId, $channel);
                break;
        }

        return $count;
    }
}
