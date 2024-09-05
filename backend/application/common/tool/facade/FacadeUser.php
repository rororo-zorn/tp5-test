<?php

namespace app\common\tool\facade;

use think\Facade;

/**
 * @see \app\common\tool\provider\User
 * @mixin \app\common\tool\provider\User
 * @method bool isLogged() static 是否已登录
 * @method void updateSessionTime() static 更新session_time
 * @method array getPrivilege() static 获取当前用户权限（菜单）
 * @method array getPrivilegeId() static 获取当前用户权限id数组
 * @method array getPrivilegeTree() static 获取当前用户权限tree
 * @method string getUserUsername() static 获取当前用户的用户名
 * @method bool validatePassword($password) static 验证当前用户密码是否正确
 * @method bool updatePassword($password) static 更新当前用户密码
 * @method void clearSession() static 清除session
 * @method integer getUserId() static 获取当前用户id
 */
class FacadeUser extends Facade
{
    /**
     * 获取当前Facade对应类名（或者已经绑定的容器对象标识）
     * @access protected
     * @return string
     */
    protected static function getFacadeClass()
    {
        return 'user';
    }
}