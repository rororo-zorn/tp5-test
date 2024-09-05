<?php

namespace app\common\tool\redis;

abstract class Backend extends MyRedis
{
    /**
     * db索引
     * @var
     */
    protected $dbIndex = 11;
}