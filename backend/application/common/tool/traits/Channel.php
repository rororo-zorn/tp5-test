<?php

namespace app\common\tool\traits;

use app\common\model\backend\Channel as model;

trait Channel
{
    /**
     * 渠道
     * @var
     */
    protected static $channel;

    /**
     * 获取渠道
     * @return array
     */
    public static function getChanel()
    {
        if (self::$channel == null) {
            self::$channel = model::getAllChannel();
        }

        return self::$channel;
    }
}