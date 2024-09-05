<?php

namespace app\common\tool\redis\gm;

use app\common\tool\redis\Game;

class GameVersion extends Game
{
    const AUDIT_ANDROID_VERSION = 'auditAndroidVersion';
    const AUDIT_IOS_VERSION = 'auditIosVersion';
    const ANDROID_VERSION = 'androidVersion';
    const IOS_VERSION = 'iosVersion';

    /**
     * key
     * @var string
     */
    protected $key = 'bc:asv:v2';

    /**
     * 默认版本
     * @var string[]
     */
    protected $defaultVersion = [
        self::AUDIT_ANDROID_VERSION => '',
        self::AUDIT_IOS_VERSION => '',
        self::ANDROID_VERSION => '',
        self::IOS_VERSION => '',
    ];

    /**
     * 获取版本
     * @return array
     */
    public function getVersion()
    {
        $version = $this->handler->hGetAll($this->key);
        return array_merge($this->defaultVersion, $version);
    }

    /**
     * 设置android审核服版本
     * @param $version
     * @return bool
     */
    public function setAndroidAuditVersion($version)
    {
        return $this->setVersion(self::AUDIT_ANDROID_VERSION, $version);
    }

    /**
     * 设置ios审核服版本
     * @param $version
     * @return bool
     */
    public function setIOSAuditVersion($version)
    {
        return $this->setVersion(self::AUDIT_IOS_VERSION, $version);
    }

    /**
     * 设置android正式服版本
     * @param $version
     * @return bool
     */
    public function setAndroidVersion($version)
    {
        return $this->setVersion(self::ANDROID_VERSION, $version);
    }

    /**
     * 设置ios正式服版本
     * @param $version
     * @return bool
     */
    public function setIOSVersion($version)
    {
        return $this->setVersion(self::IOS_VERSION, $version);
    }

    /**
     * 设置
     * @param $hashKey
     * @param $version
     * @return bool
     */
    protected function setVersion($hashKey, $version)
    {
        return $this->handler->hSet($this->key, $hashKey, $version) !== false;
    }
}