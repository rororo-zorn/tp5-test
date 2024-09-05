<?php

namespace app\index\validate\gm;

use app\common\validate\BaseValidate;
use app\index\model\gm\ServerControl as model;

class ServerControl extends BaseValidate
{
    /**
     * 定义验证规则
     */
    protected $rule = [
        'operate' => ['require', 'validateServerState'],
    ];

    /**
     * 定义错误信息
     */
    protected $message = [
        'operate.require' => '非法参数',
    ];

    /**
     * 验证公告
     * @param $value
     * @return bool|string
     */
    protected function validateServerState($value)
    {
        return $this->{$value}();
    }

    /**
     * 服务器开启判断
     * @return bool|string
     */
    protected function start()
    {
        return model::serverIsStop() ? true : '服务器已开启，请勿重复操作~';
    }

    /**
     * 服务器关闭判断
     * @return bool|string
     */
    protected function stop()
    {
        return model::serverIsStart() ? true : '服务器已关闭，请勿重复操作~';
    }
}
