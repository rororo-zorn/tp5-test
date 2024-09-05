<?php

namespace app\common\tool\pipe\gm;

use app\common\tool\pipe\Pipe;

class AdCarousel extends Pipe
{
    /**
     * 子路由
     * @var string
     */
    protected $subRoute = 'rotation_config';

    public function __construct()
    {
        $this->requestUrl = config('pipe.game_world_server') . '/' . $this->subRoute;
    }

    /**
     * send
     * @return AdCarousel
     */
    public function send()
    {
        return $this->requestServerByHTTP();
    }
}