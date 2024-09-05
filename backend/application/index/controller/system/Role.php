<?php

namespace app\index\controller\system;

use app\common\controller\BaseController;
use app\common\model\backend\Menu as menuModel;
use app\common\model\backend\Role as roleModel;
use app\index\validate\system\Role as validate;

class Role extends BaseController
{
    /**
     * 显示角色
     * @return mixed|\think\response
     */
    public function index()
    {
        if (!$this->request->isAjax()) {
            return $this->fetch('system/role/index', ['tableClos' => roleModel::getLayUiTableClos()]);
        }

        $paginator = roleModel::getPaginationData($this->request->param());
        return $this->returnToLayui($paginator);
    }

    /**
     * 新建角色
     * @return mixed
     */
    public function add()
    {
        return $this->fetch('system/role/add');
    }

    /**
     * 新建角色
     * @return \think\Response
     */
    public function doAdd()
    {
        $requestParam = $this->request->param();

        $validate = new validate();
        if (!$validate->scene('add')->check($requestParam)) {
            return $this->errorResponse($validate->getError());
        }

        $result = (new roleModel())->addRole($requestParam['role_name']);
        return $this->jsonResponse($result);
    }

    /**
     * 编辑角色名称
     * @return mixed|\think\Response
     */
    public function editRoleName()
    {
        $requestParam = $this->getDecryptRequestParam();

        $validate = new validate();
        if (!$validate->scene('editRoleName')->check($requestParam)) {
            return $this->errorResponse($validate->getError());
        }

        $model = roleModel::getRoleNameById($requestParam['id']);
        return $model ? $this->fetch('system/role/edit_role_name', ['model' => $model]) : $this->errorResponse();
    }

    /**
     * 编辑角色名称
     * @return \think\response
     */
    public function doEditRoleName()
    {
        $requestParam = $this->getDecryptRequestParam();

        $validate = new validate();
        if (!$validate->scene('doEditRoleName')->check($requestParam)) {
            return $this->errorResponse($validate->getError());
        }

        $result = roleModel::updateRoleNameById($requestParam);
        return $this->jsonResponse($result);
    }

    /**
     * 获取谷歌二维码url
     * @return \think\response
     */
    public function getGoogleQRCodeUrl()
    {
        $requestParam = $this->getDecryptRequestParam();

        $validate = new validate();
        if (!$validate->scene('authentication')->check($requestParam)) {
            return $this->errorResponse($validate->getError());
        }

        $url = roleModel::getQRCodeUrlById($requestParam['id']);
        return $url ? $this->successResponse($url) : $this->errorResponse();
    }

    /**
     * 显示角色权限
     * @return mixed
     */
    public function showPrivilege()
    {
        $requestParam = $this->getDecryptRequestParam();

        $validate = new validate();
        if (!$validate->scene('showPrivilege')->check($requestParam)) {
            return $this->errorResponse($validate->getError());
        }

        $encryptId = $this->request->param('id');
        return $this->fetch('system/role/show_privilege', ['id' => $encryptId, 'zTreeKey' => menuModel::getZTreeKey()]);
    }

    /**
     * 获取角色权限
     * @return \think\response
     */
    public function getPrivilege()
    {
        $requestParam = $this->getDecryptRequestParam();

        $validate = new validate();
        if (!$validate->scene('getPrivilege')->check($requestParam)) {
            return $this->errorResponse($validate->getError());
        }

        $privilege = roleModel::getPrivilegeTreeById($requestParam['id']);
        return $privilege ? $this->successResponse($privilege) : $this->errorResponse();
    }

    /**
     * 编辑角色权限
     * @return \think\response
     * @throws \think\exception\PDOException
     */
    public function doEditPrivilege()
    {
        $requestParam = $this->getDecryptRequestParam();

        $validate = new validate();
        if (!$validate->scene('doEditPrivilege')->check($requestParam)) {
            return $this->errorResponse($validate->getError());
        }

        $result = roleModel::updatePrivilegeById($requestParam);
        return $this->jsonResponse($result);
    }

    /**
     * 删除角色
     * @return \think\response
     */
    public function doDelete()
    {
        $requestParam = $this->getDecryptRequestParam();

        $validate = new validate();
        if (!$validate->scene('doDelete')->check($requestParam)) {
            return $this->errorResponse($validate->getError());
        }

        $result = roleModel::deleteRoleById($requestParam['id']);
        return $this->jsonResponse($result);
    }
}