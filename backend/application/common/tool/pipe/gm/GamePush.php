<?php

namespace app\common\tool\pipe\gm;

use app\common\tool\pipe\Pipe;

class GamePush extends Pipe
{
    /**
     * 子路由
     * @var string
     */
    protected $subRoute = 'push';

    /**
     * @var array
     */
    protected $param;

    /**
     * GamePush constructor.
     * @param \app\common\model\backend\gm\GamePush $model
     */
    public function __construct($model)
    {
        $this->requestUrl = config('pipe.game_push_server') . '/' . $this->subRoute;
        $this->param = $this->getParam($model);
    }

    /**
     * @param \app\common\model\backend\gm\GamePush $model
     * @return array
     */
    protected function getParam($model)
    {
        return [
            'PushType' => (int)$model->getData('push_type'),
            'PushSendType' => (int)$model->getData('send_type'),
            'Ids' => $model->getSendUid(),
            'Title' => $model->title,
            'Body' => $model->content,
            'ImageUrl' => $model->image,
        ];
    }

    /**
     * @return GamePush
     */
    public function send()
    {
        return $this->requestServerByHTTP($this->param);
    }
}