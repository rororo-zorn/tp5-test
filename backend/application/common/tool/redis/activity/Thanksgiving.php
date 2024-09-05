<?php

namespace app\common\tool\redis\activity;

use app\common\tool\redis\Game;

class Thanksgiving extends Game
{
    /**
     * 感恩节
     * @var string
     */
    protected $thanksgiving = 'gej';

    /**
     * 活动集合key
     * @var string
     */
    protected $activitySetKey = 'open_activity_set';


    /**
     * 感恩节活动key
     * @var string
     */
    protected $thanksgivingActivityKey;

    /**
     * 默认数据
     * @var array
     */
    protected $default = [
        'activityId' => 'gej',
        'activityContent' => [
            'goodsContent' => [],
        ],
    ];

    /**
     * 初始化
     */
    protected function init()
    {
        $this->thanksgivingActivityKey = sprintf('activity_details:%s', $this->thanksgiving);
    }

    /**
     * 获取默认数据
     * @return array
     */
    public function getDefaultData()
    {
        return $this->default;
    }

    /**
     * 获取数据
     * @return bool|mixed
     */
    public function getData()
    {
        if (!$this->handler->sIsMember($this->activitySetKey, $this->thanksgiving)) {
            return false;
        } else {
            $data = $this->handler->get($this->thanksgivingActivityKey);
            return json_decode($data, true);
        }
    }

    /**
     * 添加活动
     * @param $data
     * @return bool
     */
    public function addActivity($data)
    {
        return $this->handler->set($this->thanksgivingActivityKey, json_encode($data));
    }

    /**
     * 添加活动和活动集合
     * @param $data
     * @return bool
     */
    public function addAll($data)
    {
        return $this->handler->set($this->thanksgivingActivityKey, json_encode($data)) && $this->handler->sAdd($this->activitySetKey, $this->thanksgiving);
    }

    /**
     * 删除活动集合和活动
     * @return bool
     */
    public function deleteAll()
    {
        return $this->handler->sRem($this->activitySetKey, $this->thanksgiving) && $this->handler->del($this->thanksgivingActivityKey);
    }
}