<?php

namespace app\index\validate;

use app\common\validate\BaseValidate;
use app\common\model\backend\User as model;

class Login extends BaseValidate
{
    /**
     * 用户名
     * @var
     */
    protected $username;

    /**
     * user
     * @var model
     */
    protected $user;

    /**
     * 定义验证规则
     */
    protected $rule = [
        'username' => ['require', 'regex:usernameRegex', 'validateIsExist'],
        'password' => ['require', 'regex:passwordRegex', 'validateUser'],
//        'google_code' => ['require', 'number', 'length:6', 'validateGoogleCode'],
        'token_login' => ['tokenRequire', 'token:token_login'],
    ];

    /**
     * 定义错误信息
     */
    protected $message = [
        'username.require' => '请输入用户账号',
        'username.regex' => '用户账号格式错误',
        'password.require' => '请输入密码',
        'password.regex' => '密码格式错误',
//        'google_code.require' => '请输入谷歌验证码',
//        'google_code.number' => '谷歌验证码必须为数字',
        'google_code.length' => '谷歌验证码长度为6位',
    ];

    /**
     * 获取user
     * @return model
     */
    public function getUser()
    {
        if (!$this->user instanceof model) {
            $this->user = model::getUserByName($this->username);
        }

        return $this->user;
    }

    /**
     * 验证用户是否存在
     * @param $value
     * @return bool|string
     */
    protected function validateIsExist($value)
    {
        $this->username = $value;
        $user = $this->getUser();
        return !$user->isEmpty() ?: '用户名或者密码错误';
    }
    
    /**
     * 验证用户密码是否正确和是否允许登录
     * @param $value
     * @return bool|string
     */
    protected function validateUser($value)
    {
        $user = $this->getUser();

        if (!$user->validateIsAccessible()) {
            return '您的账号已被禁止登录，请联系管理员~';
        }

        if (!$user->validatePassword($value)) {
            return '用户名或者密码错误';
        }

        return true;
    }

    /**
     * 验证谷歌验证码是否正确
     * @param $value
     * @return bool|string
     */
    protected function validateGoogleCode($value)
    {
        return $this->getUser()->validateGoogleCode($value) ?: '谷歌验证码错误';
    }
}
