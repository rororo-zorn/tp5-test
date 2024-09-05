<?php

namespace app\index\validate\system;

use app\common\validate\BaseValidate;
use app\common\model\backend\User as model;

class User extends BaseValidate
{
    /**
     * 定义验证规则
     */
    protected $rule = [
        'username' => ['require', 'regex:usernameRegex', 'validateIsRepeat'],
        'password' => ['require', 'regex:passwordRegex'],
        'role_id' => ['require', 'number', 'validateRoleId'],
        'token_add_user' => ['tokenRequire', 'token:token_add_user'],
        'id' => ['require', 'number'],
        'token_edit_username' => ['tokenRequire', 'token:token_edit_username'],
        'token_edit_role_type' => ['tokenRequire', 'token:token_edit_role_type'],
        'status' => ['require', 'validateStatus'],
        'token_edit_status' => ['tokenRequire', 'token:token_edit_status'],
    ];

    /**
     * 定义错误信息
     */
    protected $message = [
        'username.require' => '请输入用户账号',
        'username.regex' => '用户账号格式错误',
        'password.require' => '请输入密码',
        'password.regex' => '密码格式错误',
        'id.require' => '非法参数',
        'id.number' => '非法参数',
        'role_id.require' => '请选择角色类型',
        'role_id.number' => '角色类型，非法参数',
        'status.require' => '请选择用户状态',
    ];

    /**
     * 定义验证场景
     * @var array
     */
    protected $scene = [
        'addUser' => ['username', 'password', 'role_id', 'token_add_user'],
        'editUsername' => ['id'],
        'doEditUsername' => ['id', 'username', 'token_edit_username'],
        'editRoleType' => ['id'],
        'doEditRoleType' => ['id', 'role_id', 'token_edit_role_type'],
        'editStatus' => ['id'],
        'doEditStatus' => ['id', 'status', 'token_edit_status'],
        'doDeleteUser' => ['id'],
    ];

    /**
     * 验证用户名是否重复
     * @param $value
     * @return bool|string
     */
    protected function validateIsRepeat($value)
    {
        return model::getUserByName($value)->isEmpty() ?: '用户账号名已存在';
    }

    /**
     * 验证角色角色id
     * @param $value
     * @return bool
     */
    protected function validateRoleId($value)
    {
        return in_array($value, array_keys(model::getRoleType())) ?: '角色类型，非法参数';
    }

    /**
     * 验证状态
     * @param $value
     * @return bool|string
     */
    protected function validateStatus($value)
    {
        return in_array($value, array_keys(model::getStatus())) ?: '用户状态，非法参数';
    }
}
