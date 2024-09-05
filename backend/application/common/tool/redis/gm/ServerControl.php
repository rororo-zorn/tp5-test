<?php

namespace app\common\tool\redis\gm;

use app\common\tool\redis\Game;

class ServerControl extends Game
{
    /**
     * key
     * @var string
     */
    protected $key = 'ssk:v1';

    /**
     * 获取服务器状态（1：open 2：close）
     * 不存在key，则默认为open
     * @return bool|mixed|string
     */
    public function getServerState()
    {
        return $this->handler->get($this->key);
    }
}