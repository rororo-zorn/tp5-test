<?php

namespace app\common\tool\redis;

abstract class Game extends MyRedis
{
    /**
     * db索引
     * @var int
     */
    protected $dbIndex = 1;
}