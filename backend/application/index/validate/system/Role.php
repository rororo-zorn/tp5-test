<?php

namespace app\index\validate\system;

use app\common\validate\BaseValidate;
use app\common\model\backend\Role as roleModel;
use app\common\tool\facade\FacadeUser;

class Role extends BaseValidate
{
    /**
     * 定义验证规则
     */
    protected $rule = [
        'role_name' => ['require', 'regex:nameRegex', 'validateIsRepeat'],
        'token_add_role' => ['tokenRequire', 'token:token_add_role'],
        'id' => ['require', 'number'],
        'token_edit_role_name' => ['tokenRequire', 'token:token_edit_role_name'],
        'privilege_ids' => 'validatePrivilege',
        'token_edit_privilege' => ['tokenRequire', 'token:token_edit_privilege'],
    ];

    /**
     * 定义错误信息
     */
    protected $message = [
        'role_name.require' => '请输入角色名称',
        'role_name.regex' => '角色名称格式错误',
        'id.require' => '非法参数',
        'id.number' => '非法参数',
    ];

    /**
     * 定义验证场景
     * @var array
     */
    protected $scene = [
        'add' => ['role_name', 'token_add_role'],
        'editRoleName' => ['id'],
        'doEditRoleName' => ['id', 'role_name', 'token_edit_role_name'],
        'authentication' => ['id'],
        'showPrivilege' => ['id'],
        'getPrivilege' => ['id'],
        'doEditPrivilege' => ['id', 'privilege_ids', 'token_edit_privilege'],
        'doDelete' => ['id'],
    ];

    /**
     * 验证角色名是否重复
     * @param $value
     * @return bool|string
     */
    protected function validateIsRepeat($value)
    {
        return roleModel::getRoleByRoleName($value)->isEmpty() ?: '请勿重复添加相同的角色名';
    }

    /**
     * 验证权限（非空数组时检测）
     * @param $value
     * @return bool|string
     */
    protected function validatePrivilege($value)
    {
        if (!is_array($value)) {
            return '权限，非法参数';
        }

        $own = FacadeUser::getPrivilegeId();
        foreach ($value as $privilegeId) {
            if (!in_array($privilegeId, $own)) {
                return '警告：权限，非法参数';
            }
        }

        return true;
    }
}
