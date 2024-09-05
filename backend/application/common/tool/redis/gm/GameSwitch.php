<?php

namespace app\common\tool\redis\gm;

use app\common\tool\redis\Game;

class GameSwitch extends Game
{
    /**
     * key
     * @var string
     */
    protected $key = 'backend_config:switch';

    /**
     * 获取所有开关
     * @return array
     */
    public function getAllSwitch()
    {
        return $this->handler->hGetAll($this->key);
    }

    /**
     * 更新开关
     * @param $hashKey
     * @param $value
     * @return bool|int
     */
    public function updateSwitch($hashKey, $value)
    {
        return $this->handler->hSet($this->key, $hashKey, $value) !== false;
    }

    /**
     * 获取开关状态
     * @param $hashKey
     * @return string
     */
    public function getSwitchState($hashKey)
    {
        return $this->handler->hGet($this->key, $hashKey);
    }
}