<?php

namespace app\index\model\gm;

use app\common\tool\redis\gm\ServerControl as redis;
use app\common\tool\pipe\gm\ServerControl as pipe;

class ServerControl
{
    /**
     * 服务器开启
     */
    const SERVER_START = 1;

    /**
     * 服务器关闭
     */
    const SERVER_CLOSE = 2;

    /**
     * layui table clos
     * @return array
     */
    public static function getLayUiTableClos()
    {
        return [
            ['field' => 'state', 'title' => '服务器状态', 'templet' => '#state'],
            ['field' => '', 'fixed' => 'right', 'width' => 100, 'align' => 'center', 'toolbar' => '#bar', 'title' => '操作'],
        ];
    }

    /**
     * 获取服务器状态（layui table）
     * @return array[]
     */
    public static function getTableData()
    {
        return [[
            'state' => self::getServerState()
        ]];
    }

    /**
     * 获取服务器状态（1：open 2：close）
     * 不存在key，则默认为open
     * @return bool|int|mixed|string
     */
    protected static function getServerState()
    {
        $status = (new redis())->getServerState();
        return $status === false ? self::SERVER_START : $status;
    }

    /**
     * 服务器是开启状态
     * @return bool
     */
    public static function serverIsStart()
    {
        return self::getServerState() == self::SERVER_START;
    }

    /**
     * 服务器是关闭状态
     */
    public static function serverIsStop()
    {
        return self::getServerState() == self::SERVER_CLOSE;
    }

    /**
     * 开启服务器
     * @return bool
     */
    public static function startServer()
    {
        return (new pipe())->optionServer(['opt' => self::SERVER_START])->isSuccess();
    }

    /**
     * 关闭服务器
     * @return bool
     */
    public static function stopServer()
    {
        return (new pipe())->optionServer(['opt' => self::SERVER_CLOSE])->isSuccess();
    }
}