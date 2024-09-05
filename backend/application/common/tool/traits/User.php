<?php

namespace app\common\tool\traits;

use app\common\model\backend\User as model;

trait User
{
    /**
     * 系统用户
     * @var
     */
    protected static $user;

    /**
     * 获取所有用户
     * @return array
     */
    public static function getUser()
    {
        if (static::$user == null) {
            static::$user = model::getAllUser();
        }

        return static::$user;
    }
}