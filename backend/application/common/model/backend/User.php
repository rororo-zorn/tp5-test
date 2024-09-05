<?php

namespace app\common\model\backend;

use app\common\model\BackendModel;
use think\model\concern\SoftDelete;

class User extends BackendModel
{
    use SoftDelete;

    /**
     * 角色类型
     * @var
     */
    protected static $roleType;

    /**
     * 软删除字段
     * @var string
     */
    protected $deleteTime = 'delete_time';

    /**
     * 软删除字段默认值
     * @var int
     */
    protected $defaultSoftDelete = 0;

    /**
     * 类型自动转换
     * @var string[]
     */
    protected $type = [
        'add_time' => 'timestamp',
        'delete_time' => 'integer'
    ];

    /**
     * 关联模型
     * @return \think\model\relation\BelongsTo
     */
    public function roleInfo()
    {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }

    /**
     * layui table clos
     * @return array
     */
    public static function getLayUiTableClos()
    {
        return [
            ['field' => 'username', 'title' => '用户账号'],
            ['field' => 'role_name', 'title' => '角色类型'],
            ['field' => 'status', 'title' => '用户状态'],
            ['field' => 'add_time', 'title' => '添加时间'],
            ['field' => '', 'fixed' => 'right', 'width' => 400, 'align' => 'center', 'toolbar' => '#bar', 'title' => '操作'],
        ];
    }

    /**
     * 获取用户状态定义
     * @return string[]
     */
    public static function getStatus()
    {
        return [
            0 => '禁止登录',
            1 => '正常',
        ];
    }

    /**
     * 获取所有用户
     * @return array
     */
    public static function getAllUser()
    {
        return self::withTrashed()->column('username', 'id');
    }

    /**
     * 获取角色类型
     * @return array
     */
    public static function getRoleType()
    {
        if (self::$roleType === null) {
            self::$roleType = Role::getRoleType();
        }

        return self::$roleType;
    }

    /**
     * 获取id加密后的角色类型（剔除了超级用户）
     * @return array
     */
    public static function getEncryptRoleType()
    {
        $roleType = [];
        foreach (self::getRoleType() as $key => $value) {
            $roleType[my_openssl_encrypt($key)] = $value;
        }

        return $roleType;
    }

    /**
     * 根据username查找用户
     * @param $name
     * @return User
     */
    public static function getUserByName($name)
    {
        return self::where('username', $name)->findOrEmpty();
    }

    /**
     * 获取用户id
     * @return mixed|null
     */
    public function getId()
    {
        return $this->getData('id');
    }

    /**
     * 获取用户账号名
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * 获取用户角色id
     * @return mixed
     */
    public function getRoleId()
    {
        return $this->role_id;
    }

    /**
     * 验证是否允许登录
     * @return bool
     */
    public function validateIsAccessible()
    {
        return boolval($this->getData('status'));
    }

    /**
     * 是否正确密码
     * @param $password
     * @return bool
     */
    public function validatePassword($password)
    {
        return password_verify($password, $this->hash_password);
    }

    /**
     * 获取hash密码
     * @param $password
     * @return false|string|null
     */
    protected function getHashPassword($password)
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    /**
     * 获取当前用户权限（菜单）
     * @return mixed
     */
    public function getPrivilege()
    {
        return $this->roleInfo->getPrivilege();
    }

    /**
     * 获取当前用户权限id数组
     * @return array
     */
    public function getPrivilegeId()
    {
        return $this->roleInfo->getPrivilegeId();
    }

    /**
     * 获取当前用户权限tree
     * @return array
     */
    public function getPrivilegeTree()
    {
        return $this->roleInfo->getPrivilegeTree();
    }

    /**
     * 验证谷歌验证码是否正确
     * @param $code
     * @return bool
     */
    public function validateGoogleCode($code)
    {
        return $this->roleInfo->validateGoogleCode($code);
    }

    /**
     * 是否是超级账号
     * @return mixed
     */
    public function isSuper()
    {
        return $this->roleInfo->isSuper();
    }

    /**
     * 是否能编辑（非超级角色即可编辑）
     * @return bool
     */
    protected function isEditable()
    {
        return !$this->roleInfo->isSuper();
    }

    /**
     * 更新用户密码
     * @param $password
     * @return bool
     */
    public function updatePassword($password)
    {
        $this->hash_password = $this->getHashPassword($password);
        return $this->save();
    }

    /**
     * 获取分页数据
     * @param $requestParam
     * @return User|\think\Paginator
     */
    public static function getPaginationData($requestParam)
    {
        $field = ['a.id AS id', 'username', 'role_name', 'status', 'a.add_time AS add_time'];
        return self::field($field)
            ->alias('a')
            ->withSearch(['username'], $requestParam)
            ->join(['bk_role' => 'b'], 'a.role_id = b.id')
            ->where('b.is_super', Role::NORMAL_ROLE)
            ->paginate($requestParam['limit']);
    }

    /**
     * 添加用户
     * @param $requestParam
     * @return bool
     * @throws \think\exception\PDOException
     */
    public function addUser($requestParam)
    {
        list('username' => $username, 'password' => $password, 'role_id' => $roleId) = $requestParam;

        $hash = $this->getHashPassword($password);
        if ($hash === false) {
            return false;
        }

        $this->startTrans();
        try {
            $data = [
                'username' => $username,
                'hash_password' => $hash,
                'role_id' => $roleId,
                'add_time' => time(),
            ];

            if ($this->save($data) && Role::increaseUserNum($roleId)) {
                $this->commit();
                return true;
            }
            throw new \Exception();
        } catch (\Exception $e) {
            $this->rollback();
            return false;
        }
    }

    /**
     * 根据id获取用户账号
     * @param $id
     * @return User|false
     */
    public static function getUsernameById($id)
    {
        $model = self::getModelById($id, ['id', 'username', 'role_id']);
        return $model->isEmpty() || !$model->isEditable() ? false : $model;
    }

    /**
     * 根据id更新用户账号
     * @param $requestParam
     * @return bool
     */
    public static function updateUsernameById($requestParam)
    {
        list('id' => $id, 'username' => $username) = $requestParam;
        $model = self::getModelById($id, ['id', 'role_id']);
        return $model->isEmpty() || !$model->isEditable() ? false : $model->save(['username' => $username]);
    }

    /**
     * 根据id获取角色id
     * @param $id
     * @return User|false
     */
    public static function getRoleIdById($id)
    {
        $model = self::getModelById($id, ['id', 'username', 'role_id']);
        return $model->isEmpty() || !$model->isEditable() ? false : $model->setEncryptRoleId();
    }

    /**
     * 设置加密
     * @return $this
     */
    protected function setEncryptRoleId()
    {
        // 不使用获取器的原因：因为bk_user使用role_id关联bk_role表的id，也会调用获取器，获取到加密的role_id
        $this->role_id = my_openssl_encrypt($this->role_id);
        return $this;
    }

    /**
     * 根据id更新用户角色id
     * @param $requestParam
     * @return bool
     * @throws \think\exception\PDOException
     */
    public static function updateRoleIdById($requestParam)
    {
        list('id' => $id, 'role_id' => $newRoleId) = $requestParam;

        $model = self::getModelById($id, ['id', 'role_id']);
        if ($model->isEmpty() || !$model->isEditable()) {
            return false;
        }

        $oldRoleId = $model->role_id;
        if ($oldRoleId == $newRoleId) {
            return false;
        }

        $model->startTrans();
        try {
            if ($model->save(['role_id' => $newRoleId]) && Role::decreaseUserNum($oldRoleId) && Role::increaseUserNum($newRoleId)) {
                $model->commit();
                return true;
            }
            throw new \Exception();
        } catch (\Exception $e) {
            $model->rollback();
            return false;
        }
    }

    /**
     * 根据id获取用户状态
     * @param $id
     * @return User|false
     */
    public static function getStatusById($id)
    {
        $model = self::getModelById($id, ['id', 'status', 'role_id']);
        if ($model->isEmpty()) {
            return false;
        }

        if (!$model->isEditable()) {
            return false;
        }

        return $model;
    }

    /**
     * 根据id更新用户状态
     * @param $requestParam
     * @return bool
     */
    public static function updateStatusById($requestParam)
    {
        list('id' => $id, 'status' => $status) = $requestParam;
        $model = self::getModelById($id, ['id', 'role_id']);
        return $model->isEmpty() || !$model->isEditable() ? false : $model->save(['status' => $status]);
    }

    /**
     * 删除用户（软删除）
     * @param $id
     * @return bool
     * @throws \think\exception\PDOException
     */
    public static function deleteUserById($id)
    {
        $model = self::getModelById($id, ['id', 'username', 'role_id']);
        if ($model->isEmpty() || !$model->isEditable()) {
            return false;
        }

        $model->startTrans();
        try {
            $roleId = $model->role_id;
            if ($model->delete() && Role::decreaseUserNum($roleId)) {
                $model->commit();
                return true;
            }
            throw new \Exception();
        } catch (\Exception $e) {
            $model->rollback();
            return false;
        }
    }

    public function getIdAttr($value)
    {
        return my_openssl_encrypt($value);
    }

    public function getStatusAttr($value)
    {
        $status = self::getStatus();
        return $status[$value];
    }

    public function searchUsernameAttr($query, $value)
    {
        if (!empty($value)) {
            $query->whereLike('username', '%' . $value . '%');
        }
    }
}