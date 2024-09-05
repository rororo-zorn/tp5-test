<?php

namespace app\common\tool\pipe\gm;

use app\common\tool\pipe\Pipe;

class GameSwitch extends Pipe
{
    /**
     * 子路由
     * @var string
     */
    protected $subRoute = 'switch_config';

    public function __construct()
    {
        $this->requestUrl = config('pipe.game_world_server') . '/' . $this->subRoute;
    }

    public function send()
    {
        return $this->requestServerByHTTP();
    }
}