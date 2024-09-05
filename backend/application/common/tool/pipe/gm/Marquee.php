<?php

namespace app\common\tool\pipe\gm;

use app\common\tool\pipe\Pipe;

class Marquee extends Pipe
{
    /**
     * 子路由
     * @var string
     */
    protected $subRoute = 'marquee';

    /**
     * @var array
     */
    protected $param;

    /**
     * Marquee constructor.
     * @param \app\common\model\backend\gm\Marquee $model
     */
    public function __construct($model)
    {
        $this->requestUrl = config('pipe.game_world_server') . '/' . $this->subRoute;
        $this->param = $this->getParam($model);
    }

    /**
     * @param \app\common\model\backend\gm\Marquee $model
     * @return array
     */
    protected function getParam($model)
    {
        return [
            'MarqueeType' => (int)$model->getData('marquee_type'),
            'MarqueeContext' => $model->content,
        ];
    }

    /**
     * @return Marquee
     */
    public function send()
    {
        return $this->requestServerByHTTP($this->param);
    }
}