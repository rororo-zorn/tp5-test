<?php

namespace app\common\model\backend;

use app\common\model\BackendModel;
use app\common\tool\facade\FacadeUser;
use PHPGangsta_GoogleAuthenticator as authenticator;

class Role extends BackendModel
{
    /**
     * 一般角色
     */
    const NORMAL_ROLE = 0;

    /**
     * 超级角色
     */
    const SUPER_ROLE = 1;

    /**
     * 类型转换
     * @var string[]
     */
    protected $type = [
        'privilege_id' => 'json',
        'privilege' => 'json',
        'add_time' => 'timestamp',
    ];

    /**
     * layui table clos
     * @return array
     */
    public static function getLayUiTableClos()
    {
        return [
            ['field' => 'role_name', 'title' => '角色名称'],
            ['field' => 'user_num', 'title' => '所属账号个数'],
            ['field' => 'add_time', 'title' => '添加时间'],
            ['field' => '', 'fixed' => 'right', 'width' => 400, 'align' => 'center', 'toolbar' => '#bar', 'title' => '操作'],
        ];
    }

    /**
     * 获取当前用户对应角色的权限（主页左侧菜单栏）
     * @return array
     */
    public function getPrivilege()
    {
        return Menu::getMenu($this->isSuper(), false, $this->privilege_id);
    }

    /**
     * 获取当前用户对应角色的权限id（菜单id）
     * @return array
     */
    public function getPrivilegeId()
    {
        return $this->isSuper() ? Menu::getAllMenuId() : $this->privilege_id;
    }

    /**
     * 获取当前用户对应角色的权限tree
     * @return array
     */
    public function getPrivilegeTree()
    {
        return Menu::getMenu($this->isSuper(), true, $this->privilege_id);
    }

    /**
     * 当前用户对应的角色是否为超级角色
     * @return bool
     */
    public function isSuper()
    {
        return $this->is_super == self::SUPER_ROLE;
    }

    /**
     * 是否能编辑（非超级角色即可编辑）
     * @return bool
     */
    protected function isEditable()
    {
        return !$this->isSuper();
    }

    /**
     * 是否正确谷歌验证码
     * @param $code
     * @return bool
     */
    public function validateGoogleCode($code)
    {
        return (new authenticator())->verifyCode($this->secret, $code);
    }

    /**
     * 根据角色名称（role_name）查找角色
     * @param $roleName
     * @return Role
     */
    public static function getRoleByRoleName($roleName)
    {
        return self::getModelById(['role_name', $roleName]);
    }

    /**
     * 获取由id和role_name构成的键值对数组
     * @return array
     */
    public static function getRoleType()
    {
        return self::where('is_super', self::NORMAL_ROLE)->column('role_name', 'id');
    }

    /**
     * 增加账号个数
     * @param $id
     * @return bool
     */
    public static function increaseUserNum($id)
    {
        $model = self::getModelById($id, ['id', 'user_num']);
        return $model->isEmpty() ? false : $model->save(['user_num' => ['inc', 1]]);
    }

    /**
     * 减少账号个数
     * @param $id
     * @return bool
     */
    public static function decreaseUserNum($id)
    {
        $model = self::getModelById($id, ['id', 'user_num']);
        return $model->isEmpty() ? false : $model->save(['user_num' => ['dec', 1]]);
    }

    /**
     * 获取分页数据
     * @param $requestParam
     * @return Role|\think\Paginator
     */
    public static function getPaginationData($requestParam)
    {
        return self::field(['secret'], true)
            ->withSearch(['role_name'], $requestParam)
            ->where('is_super', self::NORMAL_ROLE)
            ->paginate($requestParam['limit']);
    }

    /**
     * 添加角色
     * @param $roleName
     * @return bool
     */
    public function addRole($roleName)
    {
        try {
            return $this->save([
                'role_name' => $roleName,
                'secret' => (new authenticator())->createSecret(),
                'add_time' => time(),
            ]);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * 根据id获取角色名称
     * @param $id
     * @return Role|false
     */
    public static function getRoleNameById($id)
    {
        $model = self::getModelById($id, ['id', 'role_name', 'is_super']);
        return $model->isEmpty() || !$model->isEditable() ? false : $model;
    }

    /**
     * 根据id更新角色名称
     * @param $requestParam
     * @return bool
     */
    public static function updateRoleNameById($requestParam)
    {
        list('id' => $id, 'role_name' => $roleName) = $requestParam;
        $model = self::getModelById($id, ['id', 'is_super']);
        return $model->isEmpty() || !$model->isEditable() ? false : $model->save(['role_name' => $roleName]);
    }

    /**
     * 根据id获取谷歌二维码url
     * @param int $id
     * @return false|string
     */
    public static function getQRCodeUrlById($id)
    {
        $model = self::getModelById($id, ['role_name', 'secret', 'is_super']);
        return $model->isEmpty() || !$model->isEditable() ? false : (new authenticator())->getQRCodeGoogleUrl($model->role_name, $model->secret);
    }

    /**
     * 获取角色权限树
     * 角色已有权限在权限树上表现为选择状态
     * 当前用户只能查看到自己拥有的权限
     * @param int $id
     * @return mixed
     */
    public static function getPrivilegeTreeById($id)
    {
        $model = self::getModelById($id, ['privilege_id', 'is_super']);
        if ($model->isEmpty() || !$model->isEditable()) {
            return false;
        }

        $roleOwnPrivilege = $model->privilege_id;
        $selfOwnPrivilege = FacadeUser::getPrivilegeTree();
        foreach ($selfOwnPrivilege as $key => $privilege) {
            if (in_array($privilege['id'], $roleOwnPrivilege)) {
                $selfOwnPrivilege[$key]['checked'] = true;
            }
        }

        return array_values($selfOwnPrivilege);  // zTree需要的data数据的键值必须从0开始且连续
    }

    /**
     * 编辑角色权限
     * @param $requestParam
     * @return bool
     */
    public static function updatePrivilegeById($requestParam)
    {
        list('id' => $id, 'privilege_ids' => $privilegeId) = $requestParam;

        $model = self::getModelById($id, ['id', 'is_super']);
        if ($model->isEmpty() || !$model->isEditable()) {
            return false;
        }

        $privilegeId = empty($privilegeId) ? [] : $privilegeId; // 前端为空数组时，后端获取为空字符串
        array_walk($privilegeId, function (&$item) {
            return $item = intval($item);
        });

        return $model->save(['privilege_id' => $privilegeId]);
    }

    /**
     * 删除角色
     * @param $id
     * @return bool
     */
    public static function deleteRoleById($id)
    {
        $model = self::getModelById($id, ['id', 'user_num', 'is_super']);
        if ($model->isEmpty()) {
            return false;
        }

        try {
            return $model->isEditable() && $model->user_num == 0 && $model->delete();
        } catch (\Exception $e) {
            return false;
        }
    }

    public function getIdAttr($value)
    {
        return my_openssl_encrypt($value);
    }

    public function searchRoleNameAttr($query, $value)
    {
        if (!empty($value)) {
            $query->whereLike('role_name', '%' . $value . '%');
        }
    }
}