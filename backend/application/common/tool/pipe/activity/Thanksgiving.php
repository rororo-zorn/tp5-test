<?php

namespace app\common\tool\pipe\activity;

use app\common\tool\pipe\Pipe;

class Thanksgiving extends Pipe
{
    /**
     * 请求参数
     * @var string[]
     */
    protected $param = ['activityId' => 'gej'];

    /**
     * 添加、编辑、删除单个活动
     * @var string
     */
    protected $configRoute = 'change_activity';

    /**
     * 删除所有活动
     * @var string
     */
    protected $deleteRoute = 'close_activity';

    /**
     * 配置活动
     * @return Thanksgiving
     */
    public function config()
    {
        $this->requestUrl = config('pipe.game_world_server') . '/' . $this->configRoute;
        return $this->requestServerByHTTP($this->param);
    }

    /**
     * 删除活动
     * @return Thanksgiving
     */
    public function delete()
    {
        $this->requestUrl = config('pipe.game_world_server') . '/' . $this->deleteRoute;
        return $this->requestServerByHTTP($this->param);
    }
}