<?php

namespace app\common\tool\pipe\gm;

use app\common\tool\pipe\Pipe;

class Notice extends Pipe
{
    /**
     * 子路由
     * @var string
     */
    protected $subRoute = 'update_notice';

    public function __construct()
    {
        $this->requestUrl = config('pipe.game_world_server') . '/' . $this->subRoute;
    }

    /**
     * @return Notice
     */
    public function send()
    {
        return $this->requestServerByHTTP();
    }
}