<?php

namespace app\index\model\activity;

use app\common\tool\redis\activity\Thanksgiving as redis;
use app\common\tool\pipe\activity\Thanksgiving as pipe;

abstract class Thanksgiving
{
    /**
     * 活动关闭
     */
    const ACTIVITY_CLOSE = 0;

    /**
     * 活动开启
     */
    const ACTIVITY_OPEN = 1;

    /**
     * 功能-无
     */
    const FUNCTION_DEFAULT = 0;

    /**
     * 功能-折扣
     */
    const FUNCTION_DISCOUNT = 1;

    /**
     * 功能-翻倍
     */
    const FUNCTION_MULTIPLICITY = 2;

    /**
     * 类型
     * @var
     */
    protected $type;

    /**
     * redis
     * @var redis
     */
    protected $redis;

    /**
     * 是否存在
     * @var bool
     */
    protected $isOpen = false;

    /**
     * 验证场景
     * @var array
     */
    protected $validateScent = [];

    /**
     * redis data
     * @var array
     */
    protected $redisData = [];

    /**
     * 数据
     * @var array
     */
    protected $data = [];

    /**
     * 获取活动状态类型
     * @return int[]
     */
    public static function getIsOpenType()
    {
        return [self::ACTIVITY_CLOSE, self::ACTIVITY_OPEN];
    }

    public function __construct()
    {
        $this->redis = new redis();
        $this->redisData = $this->redis->getData();

        if ($this->redisData) {
            $this->setData();
        }
    }

    /**
     * 设置data
     */
    public function setData()
    {
        $content = $this->redisData['activityContent']['goodsContent'];

        foreach ($content as $value) {
            if ($value['type'] == $this->type) {
                $this->data = $value;
                $this->data['startTime'] = date('Y-m-d H:i:s', $value['startTime']);
                $this->data['endTime'] = date('Y-m-d H:i:s', $value['endTime']);
                $this->isOpen = true;
                break;
            }
        }
    }

    /**
     * 获取是否存在结果
     * @return bool
     */
    public function getIsOpen()
    {
        return $this->isOpen;
    }

    /**
     * 获取验证场景
     * @param $isOpen
     * @return mixed
     */
    public function getValidateScene($isOpen)
    {
        return $this->validateScent[$isOpen];
    }

    /**
     * 处理开启、编辑、关闭活动
     * @param $requestParam
     * @return bool
     */
    public function deal($requestParam)
    {
        if ($requestParam['isOpen'] == self::ACTIVITY_OPEN) {
            return $this->add($requestParam);
        }

        return $this->delete();
    }

    /**
     * 添加活动
     * @param $requestParam
     * @return bool
     */
    protected function add($requestParam)
    {
        $content = $this->getContent($requestParam);
        if ($this->getIsOpen() || $this->redisData) {
            // 先删除
            $goodsContent = $this->redisData['activityContent']['goodsContent'];
            foreach ($goodsContent as $key => $value) {
                if ($value['type'] == $this->type) {
                    unset($goodsContent[$key]);
                    break;
                }
            }

            // 添加
            $goodsContent[] = $content;
            $this->redisData['activityContent']['goodsContent'] = array_values($goodsContent);

            return $this->redis->addActivity($this->redisData) && (new pipe())->config()->isSuccess();
        }

        $data = $this->redis->getDefaultData();
        $data['activityContent']['goodsContent'][] = $content;
        return $this->redis->addAll($data) && (new pipe())->config()->isSuccess();
    }

    /**
     * 获取存储redis的数据
     * @param $requestParam
     * @return array
     */
    protected function getContent($requestParam)
    {
        return [
            'startTime' => strtotime($requestParam['startTime']),
            'endTime' => strtotime($requestParam['endTime']),
            'param' => (int)$requestParam['param'],
            'type' => $this->type,
            'goodsId' => 0, // 默认
            'operation' => self::FUNCTION_MULTIPLICITY,   // 默认翻倍
        ];
    }

    /**
     * 删除活动
     * @return bool
     */
    protected function delete()
    {
        $goodsContent = $this->redisData['activityContent']['goodsContent'];
        foreach ($goodsContent as $key => $value) {
            if ($value['type'] == $this->type) {
                unset($goodsContent[$key]);
                break;
            }
        }

        $this->redisData['activityContent']['goodsContent'] = array_values($goodsContent);

        // 删除当前活动
        if (count($this->redisData['activityContent']['goodsContent'])) {
            return $this->redis->addActivity($this->redisData) && (new pipe())->config()->isSuccess();
        }

        // 删除所有
        return $this->redis->deleteAll() && (new pipe())->delete()->isSuccess();
    }

    /**
     * 魔法方法
     * @param $name
     * @return mixed|string
     */
    public function __get($name)
    {
        return $this->isOpen ? $this->data[$name] : '';
    }
}