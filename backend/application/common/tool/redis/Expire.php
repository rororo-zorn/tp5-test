<?php

namespace app\common\tool\redis;

abstract class Expire extends MyRedis
{
    /**
     * db索引
     * @var
     */
    protected $dbIndex = 10;
}