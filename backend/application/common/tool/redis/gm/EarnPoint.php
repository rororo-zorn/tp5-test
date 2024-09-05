<?php

namespace app\common\tool\redis\gm;

use app\common\tool\redis\Game;

class EarnPoint extends Game
{
    /**
     * key
     * @var string
     */
    protected $key = 'backend_config:integral';

    /**
     * 获取所有配置
     * @return array
     */
    public function getAllConfig()
    {
        return $this->handler->hGetAll($this->key);
    }

    /**
     * 根据哈希key获取配置
     * @param $hashKey
     * @return array|mixed
     */
    public function getConfigByHashKey($hashKey)
    {
        $config = $this->handler->hGet($this->key, $hashKey);
        if ($config === false) {
            $config = [];
        } else {
            $config = json_decode($config, true);
        }

        return $config;
    }

    /**
     * 设置配置
     * @param $hashKey
     * @param $value
     * @return bool|int
     */
    public function setConfig($hashKey, $value)
    {
        $result = $this->handler->hSet($this->key, $hashKey, json_encode($value));
        return $result !== false;
    }
}