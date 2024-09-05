<?php

namespace app\index\model\gm;

use app\common\tool\redis\gm\Whitelist as redis;
use app\common\tool\pipe\gm\Whitelist as pipe;

class Whitelist
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
     * 获取白名单
     * @param $requestParam
     * @return array|array[]
     */
    public static function getWhitelist($requestParam)
    {
        $uid = !empty($requestParam['uid']) ? $requestParam['uid'] : false;
        return $uid ? self::getSpecifyWhitelist($uid) : self::getAllWhitelist();
    }

    /**
     * 获取指定白名单
     * @param $uid
     * @return array
     */
    protected static function getSpecifyWhitelist($uid)
    {
        return (new redis())->isInWhitelist($uid) ? [['uid' => $uid]] : [];
    }

    /**
     * 获取所有白名单
     * @return array
     */
    protected static function getAllWhitelist()
    {
        $uidArr = (new redis())->getWhitelist();
        $data = [];
        foreach ($uidArr as $uid) {
            $data[] = ['uid' => $uid];
        }

        return $data;
    }

    /**
     * 添加白名单
     * @param $uid
     * @return bool
     */
    public static function addWhitelist($uid)
    {
        $redis = new redis();
        if ($redis->addWhitelist($uid)) {
            if ((new pipe())->addWhitelist($uid)->isSuccess()) {
                return true;
            } else {
                $redis->deleteWhitelist($uid);
                return false;
            }
        }

        return false;
    }

    /**
     * 删除白名单
     * @param $uid
     * @return int
     */
    public static function deleteWhitelist($uid)
    {
        $redis = new redis();
        if ($redis->deleteWhitelist($uid)) {
            if ((new pipe())->deleteWhitelist($uid)->isSuccess()) {
                return true;
            } else {
                $redis->addWhitelist($uid);
                return false;
            }
        }

        return false;
    }
}