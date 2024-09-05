<?php

namespace app\common\tool\pipe\gm;

use app\common\tool\pipe\Pipe;

class EarnPoint extends Pipe
{
    /**
     * 子路由
     * @var string
     */
    protected $subRoute = 'integral_config';

    public function __construct()
    {
        $this->requestUrl = config('pipe.game_world_server') . '/' . $this->subRoute;
    }

    /**
     * send
     * @return EarnPoint
     */
    public function send()
    {
        return $this->requestServerByHTTP();
    }
}