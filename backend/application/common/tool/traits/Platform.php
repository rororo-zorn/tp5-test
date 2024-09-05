<?php

namespace app\common\tool\traits;

use app\common\model\backend\Platform as model;

trait Platform
{
    /**
     * 平台
     * @var
     */
    protected static $platform;

    /**
     * 获取渠道
     * @return array
     */
    public static function getPlatform()
    {
        if (self::$platform == null) {
            self::$platform = model::getAllPlatform();
        }

        return self::$platform;
    }
}