<?php

namespace app\common\tool\pipe\gm;

use app\common\tool\pipe\Pipe;

class Blacklist extends Pipe
{
    /**
     * 添加黑名单
     * @var int
     */
    protected $addBlacklist = 0;

    /**
     * 删除黑名单
     * @var int
     */
    protected $deleteBlacklist = 1;

    /**
     * 子路由
     * @var string
     */
    protected $subRoute = 'blacklist';

    public function __construct()
    {
        $this->requestUrl = config('pipe.game_world_server') . '/' . $this->subRoute;
    }

    /**
     * 添加黑名单
     * @param $uid
     * @return Blacklist
     */
    public function addBlacklist($uid)
    {
        return $this->requestServerByHTTP([
            'opt' => $this->addBlacklist,
            'uid' => intval($uid),
        ]);
    }

    /**
     * 删除黑名单
     * @param $uid
     * @return Blacklist
     */
    public function deleteBlacklist($uid)
    {
        return $this->requestServerByHTTP([
            'opt' => $this->deleteBlacklist,
            'uid' => intval($uid),
        ]);
    }
}