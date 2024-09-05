<?php

namespace app\index\validate\query;

use app\common\validate\BaseValidate;
use app\common\model\game\User;

class Player extends BaseValidate
{
    /**
     * 定义验证规则
     */
    protected $rule = [
        'uid' => ['require', 'number', 'validateUid'],
    ];

    /**
     * 定义错误信息
     */
    protected $message = [
        'uid.require' => '请输入玩家ID',
        'uid.regex' => '玩家ID格式错误',
    ];

    /**
     * 验证uid是否存在
     * @param $value
     * @return bool|string
     */
    protected function validateUid($value)
    {
        $model = User::getModelById(['uid', $value], ['uid']);
        return $model->isEmpty() ? '玩家ID不存在~' : true;
    }
}
