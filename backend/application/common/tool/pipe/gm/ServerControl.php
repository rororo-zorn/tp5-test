<?php

namespace app\common\tool\pipe\gm;

use app\common\tool\pipe\Pipe;

class ServerControl extends Pipe
{
    /**
     * 子路由
     * @var string
     */
    protected $subRoute = 'optionserver';

    public function __construct()
    {
        $this->requestUrl = config('pipe.game_world_server') . '/' . $this->subRoute;
    }

    /**
     * 启停服务器
     * @param $param
     * @return ServerControl
     */
    public function optionServer($param)
    {
        return $this->requestServerByHTTP($param);
    }
}