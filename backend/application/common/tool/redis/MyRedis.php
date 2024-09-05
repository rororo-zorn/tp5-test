<?php

namespace app\common\tool\redis;

abstract class MyRedis
{
    /**
     * 驱动句柄
     * @var \Redis
     */
    protected $handler;

    /**
     * db索引
     * @var
     */
    protected $dbIndex;

    public function __construct()
    {
        $config = config('redis.');
        $this->handler = new \Redis();
        $this->handler->connect($config['host'], $config['port']);
        if ($config['pwd'] != '') {
            if ($config['user'] != '') {
                $config['pwd'] = [$config['user'], $config['pwd']]; // tls
            }
            $this->handler->auth($config['pwd']);
        }

        $this->handler->select($this->dbIndex);

        $this->init();
    }

    protected function init()
    {
    }

    public function __destruct()
    {
        $this->handler->close();
    }
}