<?php

namespace app\common\tool\pipe\gm;

use app\common\tool\pipe\Pipe;

class Whitelist extends Pipe
{
    /**
     * 添加白名单
     * @var string
     */
    protected $addRoute = 'addwhitelist';

    /**
     * 删除白名单
     * @var string
     */
    protected $deleteRoute = 'delwhitelist';

    /**
     * 添加黑名单
     * @param $uid
     * @return Whitelist
     */
    public function addWhitelist($uid)
    {
        $this->requestUrl = config('pipe.game_world_server') . '/' . $this->addRoute;
        return $this->requestServerByHTTP([
            'uid' => intval($uid)
        ]);
    }

    /**
     * 删除黑名单
     * @param $uid
     * @return Whitelist
     */
    public function deleteWhitelist($uid)
    {
        $this->requestUrl = config('pipe.game_world_server') . '/' . $this->deleteRoute;
        return $this->requestServerByHTTP([
            'uid' => intval($uid)
        ]);
    }
}