<?php

namespace app\common\tool\redis\operation;

use app\common\tool\redis\Backend;

class OperationData extends Backend
{
    /**
     * 最后一次执行的时间戳
     * @var string
     */
    protected $lastExecuteTimestamp = 'lastExecuteTimestamp';

    /**
     * 平台渠道
     * @var string
     */
    protected $pc = 'platformChannel';

    /**
     * 时间戳-平台-渠道-类型
     * @var string
     */
    protected $keyFormat = '%d:%d:%s:%s';

    /**
     * 新增用户
     * @var string
     */
    protected $newUser = 'newUser';

    /**
     * 活跃用户
     * @var string
     */
    protected $activeUser = 'activeUser';

    /**
     * 破产用户
     * @var string
     */
    protected $bankruptcyUser = 'bankruptcyUser';

    /**
     * 获取key
     * @param $dailyTime
     * @param $pId
     * @param $channel
     * @param $type
     * @return string
     */
    protected function getKey($dailyTime, $pId, $channel, $type)
    {
        return sprintf($this->keyFormat, $dailyTime, $pId, $channel, $type);
    }

    /**
     * 获取最后一次执行任务时间戳
     * @return bool|mixed|string
     */
    public function getLastExecuteTime()
    {
        return $this->handler->get($this->lastExecuteTimestamp);
    }

    /**
     * 设置最后一次执行任务时间戳
     * @param $timestamp
     * @return bool
     */
    public function setLastExecuteTimestamp($timestamp)
    {
        return $this->handler->set($this->lastExecuteTimestamp, $timestamp);
    }

    /**
     * 获取平台渠道
     * @return array
     */
    public function getPc()
    {
        $pc = [];
        $pcStrArr = $this->handler->sMembers($this->pc);
        foreach ($pcStrArr as $pcStr) {
            $pc[] = explode('-', $pcStr);
        }

        return $pc;
    }

    /**
     * 获取新增用户
     * @param $dailyTime
     * @param $pId
     * @param $channel
     * @return array
     */
    public function getNewUser($dailyTime, $pId, $channel)
    {
        $key = $this->getKey($dailyTime, $pId, $channel, $this->newUser);
        return $this->handler->sMembers($key);
    }

    /**
     * 获取活跃用户
     * @param $dailyTime
     * @param $pId
     * @param $channel
     * @return array
     */
    public function getActiveUser($dailyTime, $pId, $channel)
    {
        $key = $this->getKey($dailyTime, $pId, $channel, $this->activeUser);
        return $this->handler->sMembers($key);
    }

    /**
     * 获取破产用户
     * @param $dailyTime
     * @param $pId
     * @param $channel
     * @return array
     */
    public function getBankruptcyUser($dailyTime, $pId, $channel)
    {
        $key = $this->getKey($dailyTime, $pId, $channel, $this->bankruptcyUser);
        return $this->handler->sMembers($key);
    }

    /**
     * 根据零点时间戳、平台ID、渠道名获取新增用户数量
     * @param $dailyTime
     * @param $pId
     * @param $channel
     * @return int
     */
    public function getNewUserNum($dailyTime, $pId, $channel)
    {
        $key = $this->getKey($dailyTime, $pId, $channel, $this->newUser);
        return $this->handler->sCard($key);
    }

    /**
     * 获取新增用户留存数量
     * 计算公式:n日新增用户在第n+1日，n+2日等活跃数量
     * 例如：指定天新增用户为5人，第二日这5人中有3人活跃，则指定天的次日留存为：3/5=60%
     * @param $dailyTime
     * @param $assignTime
     * @param $pId
     * @param $channel
     * @return int
     */
    public function getNewUserRetention($dailyTime, $assignTime, $pId, $channel)
    {
        $active = $this->getKey($dailyTime, $pId, $channel, $this->activeUser);
        $new = $this->getKey($assignTime, $pId, $channel, $this->newUser);
        return count($this->handler->sInter($active, $new));
    }

    /**
     * 获取活跃用户留存数量
     * @param $dailyTime
     * @param $assignTime
     * @param $pId
     * @param $channel
     * @return int
     */
    public function getActiveUserRetention($dailyTime, $assignTime, $pId, $channel)
    {
        $active1 = $this->getKey($dailyTime, $pId, $channel, $this->activeUser);
        $active2 = $this->getKey($assignTime, $pId, $channel, $this->activeUser);
        return count($this->handler->sInter($active1, $active2));
    }

    /**
     * 获取破产用户留存数量
     * @param $dailyTime
     * @param $assignTime
     * @param $pId
     * @param $channel
     * @return int
     */
    public function getBankruptcyUserRetention($dailyTime, $assignTime, $pId, $channel)
    {
        $active = $this->getKey($dailyTime, $pId, $channel, $this->activeUser);
        $bankruptcy = $this->getKey($assignTime, $pId, $channel, $this->bankruptcyUser);
        return count($this->handler->sInter($active, $bankruptcy));
    }

    /**
     * 删除redis缓存
     * @param $assignTime
     * @param $pc
     * @return int
     */
    public function deleteStatisticsData($assignTime, $pc)
    {
        $keys = $this->getDeleteKey($assignTime, $pc);
        return $this->handler->del($keys);
    }

    /**
     * 获取删除的keys
     * @param $assignTime
     * @param $pc
     * @return array
     */
    protected function getDeleteKey($assignTime, $pc)
    {
        $keys = [];
        $types = [$this->newUser, $this->activeUser, $this->bankruptcyUser];
        foreach ($pc as $item) {
            list($pId, $channel) = $item;
            foreach ($types as $type) {
                $keys[] = $this->getKey($assignTime, $pId, $channel, $type);
            }
        }

        return $keys;
    }
}