<?php

namespace app\common\tool\provider;

use think\facade\Session;
use app\common\model\backend\User as userModel;

class User
{
    /**
     * session字段
     * @var string[]
     */
    protected $sessionField = [
        'id' => 'session_id',
        'time' => 'session_time'
    ];

    /**
     * session有效期
     * @var
     */
    protected $validityPeriod = 86400;

    /**
     * session id
     * @var
     */
    protected $sessionId;

    /**
     * session time
     * @var
     */
    protected $sessionTime;

    /**
     * 是否已登录
     * @var bool
     */
    protected $isLogged = false;

    /**
     * 用户
     * @var userModel
     */
    protected $user;

    public function __construct()
    {
        $this->sessionId = Session::get($this->sessionField['id']);
        $this->sessionTime = Session::get($this->sessionField['time']);
        $this->setIsLogged();
    }

    /**
     * 设置是否已登录
     */
    protected function setIsLogged()
    {
        $this->isLogged = $this->isValidSession() && $this->isExistingAndAccessibleUser();
    }

    /**
     * 是否有效session
     * @return bool
     */
    protected function isValidSession()
    {
        return $this->sessionId && $this->sessionTime && ($this->sessionTime + $this->validityPeriod) > time();
    }

    /**
     * 是否存在且允许登录的用户
     * @return bool
     */
    protected function isExistingAndAccessibleUser()
    {
        $this->user = userModel::getModelById($this->sessionId);
        return !$this->user->isEmpty() && $this->user->validateIsAccessible();
    }

    /**
     * 是否已登录
     * @return bool
     */
    public function isLogged()
    {
        return $this->isLogged;
    }

    /**
     * 获取当前用户权限（菜单）
     * @return array
     */
    public function getPrivilege()
    {
        return $this->user->getPrivilege();
    }

    /**
     * 获取当前用户权限id数组
     * @return array
     */
    public function getPrivilegeId()
    {
        return $this->user->getPrivilegeId();
    }

    /**
     * 获取当前用户权限tree
     * @return array
     */
    public function getPrivilegeTree()
    {
        return $this->user->getPrivilegeTree();
    }

    /**
     * 更新session_time
     */
    public function updateSessionTime()
    {
        Session::set($this->sessionField['time'], time());
    }

    /**
     * 获取当前用户id
     * @return mixed
     */
    public function getUserId()
    {
        return $this->user->getId();
    }

    /**
     * 获取当前用户的用户名
     * @return mixed
     */
    public function getUserUsername()
    {
        return $this->user->getUsername();
    }

    /**
     * 验证当前用户密码是否正确
     * @param $password
     * @return bool
     */
    public function validatePassword($password)
    {
        return $this->user->validatePassword($password);
    }

    /**
     * 清除session
     */
    public function clearSession()
    {
        Session::clear();
    }

    /**
     * 更新当前用户密码
     * @param $password
     * @return bool
     */
    public function updatePassword($password)
    {
        return $this->user->updatePassword($password);
    }
}