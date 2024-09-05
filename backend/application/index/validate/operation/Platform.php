<?php

namespace app\index\validate\operation;

use app\common\validate\BaseValidate;
use app\common\model\backend\Platform as model;

class Platform extends BaseValidate
{
    /**
     * 定义验证规则
     */
    protected $rule = [
        'id' => ['require', 'number'],
        'platform_name' => ['require', 'regex:nameRegex', 'validateIsRepeat:platform_name'],
        'token_edit_platform' => ['tokenRequire', 'token:token_edit_platform'],
    ];

    /**
     * 定义错误信息
     */
    protected $message = [
        'id.require' => '非法参数',
        'id.number' => '非法参数',
        'platform_name.require' => '请输入平台名称',
        'platform_name.regex' => '平台名称格式错误',
    ];

    /**
     * 定义验证场景
     * @var array
     */
    protected $scene = [
        'edit' => ['id'],
        'doEdit' => ['id', 'platform_name', 'token_edit_platform'],
    ];

    /**
     * 验证平台名是否重复
     * @param $value
     * @param $rule
     * @return bool|string
     */
    protected function validateIsRepeat($value, $rule)
    {
        return model::getModelById([$rule, $value])->isEmpty() ?: '平台名未改变或者平台名已存在';
    }
}
