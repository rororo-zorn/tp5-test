<?php

namespace app\common\tool\pipe\gm;

use app\common\tool\pipe\Pipe;

class Email extends Pipe
{
    /**
     * 子路由
     * @var string
     */
    protected $subRoute = 'sendmail';

    protected $param;

    /**
     * @var \app\common\model\backend\gm\Email
     */
    protected $model;

    public function __construct($model)
    {
        $this->model = $model;
        $this->requestUrl = config('pipe.game_world_server') . '/' . $this->subRoute;

        $this->setParam();
    }

    /**
     * 设置参数
     */
    protected function setParam()
    {
        $this->param = [
            'theme' => $this->model->theme,
            'title' => $this->model->title,
            'content' => $this->model->content,
            'tail' => $this->model->tail,
            'item' => json_decode($this->model->getData('item'), true),
            'broadCast' => $this->model->isSendToAll(),
            'uid' => $this->model->uid,
            'sendTime' => $this->model->getData('send_time'),
        ];
    }

    /**
     * send
     * @return Email
     */
    public function send()
    {
        return $this->requestServerByHTTP([
            'broadCast' => $this->param['broadCast'],
            'infos' => $this->getInfo(),
        ]);
    }

    /**
     * 获取info
     * @return array
     */
    protected function getInfo()
    {
        $item = $this->getItem();
        $uidArr = $this->getUid();

        $info = [];
        foreach ($uidArr as $uid) {
            $info[] = [
                'uid' => (int)($uid),
                'theme' => $this->param['theme'],
                'title' => $this->param['title'],
                'content' => $this->param['content'],
                'tail' => $this->param['tail'],
                'extra' => [
                    'items' => $item,
                ],
            ];
        }

        return $info;
    }

    /**
     * 获取附件
     * @return mixed
     */
    protected function getItem()
    {
        $item = $this->param['item'];
        array_walk_recursive($item, function (&$item) {
            $item = intval($item);
        });

        return $item;
    }

    /**
     * 获取uid
     * @return false|int[]|string[]
     */
    protected function getUid()
    {
        return $this->param['broadCast'] ? [0] : explode(',', $this->param['uid']);
    }
}