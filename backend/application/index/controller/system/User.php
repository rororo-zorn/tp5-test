<?php

namespace app\index\controller\system;

use app\common\controller\BaseController;
use app\common\model\backend\User as model;
use app\index\validate\system\User as validate;

class User extends BaseController
{
    /**
     * 显示用户
     * @return mixed|\think\response
     */
    public function index()
    {
        if (!$this->request->isAjax()) {
            return $this->fetch('system/user/index', ['tableClos' => model::getLayUiTableClos()]);
        }

        $paginator = model::getPaginationData($this->request->param());
        return $this->returnToLayui($paginator);
    }

    /**
     * 添加用户
     * @return mixed
     */
    public function addUser()
    {
        return $this->fetch('system/user/add_user', ['roleType' => model::getEncryptRoleType()]);
    }

    /**
     * 添加用户操作
     * @return \think\Response
     * @throws \think\exception\PDOException
     */
    public function doAddUser()
    {
        $requestParam = $this->getDecryptRequestParam();

        $validate = new validate();
        if (!$validate->scene('addUser')->check($requestParam)) {
            return $this->errorResponse($validate->getError());
        }

        $result = (new model())->addUser($requestParam);
        return $this->jsonResponse($result);
    }

    /**
     * 编辑用户账号
     * @return mixed|\think\response
     */
    public function editUsername()
    {
        $requestParam = $this->getDecryptRequestParam();

        $validate = new validate();
        if (!$validate->scene('editUsername')->check($requestParam)) {
            return $this->errorResponse($validate->getError());
        }

        $model = model::getUsernameById($requestParam['id']);
        return $model ? $this->fetch('system/user/edit_username', ['model' => $model]) : $this->errorResponse();
    }

    /**
     * 编辑用户账号
     * @return \think\Response
     */
    public function doEditUsername()
    {
        $requestParam = $this->getDecryptRequestParam();

        $validate = new validate();
        if (!$validate->scene('doEditUsername')->check($requestParam)) {
            return $this->errorResponse($validate->getError());
        }

        $result = model::updateUsernameById($requestParam);
        return $this->jsonResponse($result);
    }

    /**
     * 编辑用户角色类型
     * @return mixed|\think\response
     */
    public function editRoleType()
    {
        $requestParam = $this->getDecryptRequestParam();

        $validate = new validate();
        if (!$validate->scene('editRoleType')->check($requestParam)) {
            return $this->errorResponse($validate->getError());
        }

        $model = model::getRoleIdById($requestParam['id']);
        return $model ? $this->fetch('system/user/edit_role_type', [
            'model' => $model,
            'roleType' => model::getEncryptRoleType()
        ]) : $this->errorResponse();
    }

    /**
     * 编辑用户角色类型
     * @return \think\Response
     * @throws \think\exception\PDOException
     */
    public function doEditRoleType()
    {
        $requestParam = $this->getDecryptRequestParam();

        $validate = new validate();
        if (!$validate->scene('doEditRoleType')->check($requestParam)) {
            return $this->errorResponse($validate->getError());
        }

        $result = model::updateRoleIdById($requestParam);
        return $this->jsonResponse($result);
    }

    /**
     * 编辑用户状态
     * @return mixed|\think\response
     */
    public function editStatus()
    {
        $requestParam = $this->getDecryptRequestParam();

        $validate = new validate();
        if (!$validate->scene('editStatus')->check($requestParam)) {
            return $this->errorResponse($validate->getError());
        }

        $model = model::getStatusById($requestParam['id']);
        return $model ? $this->fetch('system/user/edit_status', [
            'model' => $model,
            'status' => model::getStatus()
        ]) : $this->errorResponse();
    }

    /**
     * 编辑用户状态
     * @return \think\Response
     */
    public function doEditStatus()
    {
        $requestParam = $this->getDecryptRequestParam();

        $validate = new validate();
        if (!$validate->scene('doEditStatus')->check($requestParam)) {
            return $this->errorResponse($validate->getError());
        }

        $result = model::updateStatusById($requestParam);
        return $this->jsonResponse($result);
    }

    /**
     * 删除用户
     * @return \think\Response
     * @throws \think\exception\PDOException
     */
    public function doDeleteUser()
    {
        $requestParam = $this->getDecryptRequestParam();

        $validate = new validate();
        if (!$validate->scene('doDeleteUser')->check($requestParam)) {
            return $this->errorResponse($validate->getError());
        }

        $result = model::deleteUserById($requestParam['id']);
        return $this->jsonResponse($result);
    }
}
