<?php

namespace app\index\model\gm;

use app\common\tool\redis\gm\GameVersion as redis;

class GameVersion
{
    /**
     * 版本
     * @var array
     */
    protected $version;

    public function __construct()
    {
        $this->version = (new redis())->getVersion();
    }

    /**
     * 获取版本
     * @return array
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * android审核服版本配置
     * @param $version
     * @return bool
     */
    public static function androidAuditConfig($version)
    {
        return (new redis())->setAndroidAuditVersion($version);
    }

    /**
     * ios审核服版本配置
     * @param $version
     * @return bool
     */
    public static function iosAuditConfig($version)
    {
        return (new redis())->setIOSAuditVersion($version);
    }

    /**
     * android正式服版本配置
     * @param $version
     * @return bool
     */
    public static function androidConfig($version)
    {
        return (new redis())->setAndroidVersion($version);
    }

    /**
     * ios正式服版本配置
     * @param $version
     * @return bool
     */
    public static function iosConfig($version)
    {
        return (new redis())->setIOSVersion($version);
    }
}