<?php

namespace app\index\validate;

use app\common\tool\facade\FacadeUser;
use app\common\validate\BaseValidate;

class Index extends BaseValidate
{
    /**
     * 定义验证规则
     */
    protected $rule = [
        'now_password' => ['require', 'regex:passwordRegex', 'validatePassword'],
        'new_password' => ['require', 'regex:passwordRegex', 'different:now_password'],
        'new_password_confirm' => ['require', 'confirm'],
        'token_edit_password' => ['tokenRequire', 'token:token_edit_password'],
    ];

    /**
     * 定义错误信息
     */
    protected $message = [
        'now_password.require' => '请输入密码',
        'now_password.regex' => '密码格式错误',
        'new_password.require' => '请输入新密码',
        'new_password.regex' => '新密码格式错误',
        'new_password.different' => '不能重复设置相同的密码',
        'new_password_confirm.require' => '请输入确认密码',
        'new_password_confirm.confirm' => '确认密码和新密码不一致',
    ];

    /**
     * 验证当前密码是否正确
     * @param $value
     * @return bool|string
     */
    protected function validatePassword($value)
    {
        return FacadeUser::validatePassword($value) ?: '当前密码错误';
    }
}
