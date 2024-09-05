<?php

namespace app\index\model\gm;

use app\common\tool\redis\gm\Blacklist as redis;
use app\common\tool\pipe\gm\Blacklist as pipe;

class Blacklist
{
    /**
     * layui table clos
     * @return array
     */
    public static function getLayUiTableClos()
    {
        return [
//            ['type' => 'checkbox', 'fixed' => 'left'],
            ['field' => 'uid', 'title' => '玩家ID'],
            ['field' => '', 'fixed' => 'right', 'width' => 100, 'align' => 'center', 'toolbar' => '#bar', 'title' => '操作'],
        ];
    }

    /**
     * 获取黑名单
     * @param $requestParam
     * @return array|array[]
     */
    public static function getBlacklist($requestParam)
    {
        $uid = !empty($requestParam['uid']) ? $requestParam['uid'] : false;
        return $uid ? self::getSpecifyBlacklist($uid) : self::getAllBlacklist();
    }

    /**
     * 获取指定黑名单
     * @param $uid
     * @return array
     */
    protected static function getSpecifyBlacklist($uid)
    {
        return (new redis())->isInBlacklist($uid) ? [['uid' => $uid]] : [];
    }

    /**
     * 获取所有黑名单
     * @return array
     */
    protected static function getAllBlacklist()
    {
        $uidArr = (new redis())->getBlacklist();
        $data = [];
        foreach ($uidArr as $uid) {
            $data[] = ['uid' => $uid];
        }

        return $data;
    }

    /**
     * 添加黑名单
     * @param $uid
     * @return bool
     */
    public static function addBlacklist($uid)
    {
        $redis = new redis();
        if ($redis->addBlacklist($uid)) {
            if ((new pipe())->addBlacklist($uid)->isSuccess()) {
                return true;
            } else {
                $redis->deleteBlacklist($uid);
                return false;
            }
        }

        return false;
    }

    /**
     * 删除黑名单
     * @param $uid
     * @return int
     */
    public static function deleteBlacklist($uid)
    {
        $redis = new redis();
        if ($redis->deleteBlacklist($uid)) {
            if ((new pipe())->deleteBlacklist($uid)->isSuccess()) {
                return true;
            } else {
                $redis->addBlacklist($uid);
                return false;
            }
        }

        return false;
    }
}