<?php

namespace app\index\controller\system;

use app\common\controller\BaseController;
use app\common\model\backend\Menu as model;
use app\index\validate\system\Menu as validate;

class Menu extends BaseController
{
    /**
     * 显示菜单列表
     * @return mixed|\think\response
     */
    public function index()
    {
        if (!$this->request->isAjax()) {
            return $this->fetch('system/menu/index', ['tableClos' => model::getLayUiTableClos()]);
        }

        $paginator = model::getPaginationData($this->request->param());
        return $this->returnToLayui($paginator);
    }

    /**
     * 编辑菜单
     * @return mixed|\think\response
     */
    public function edit()
    {
        $requestParam = $this->getDecryptRequestParam();

        $validate = new validate();
        if (!$validate->scene('edit')->check($requestParam)) {
            return $this->errorResponse($validate->getError());
        }

        $model = model::getEditMenu($requestParam['id']);
        return $model ? $this->fetch('system/menu/edit', ['model' => $model]) : $this->errorResponse();
    }

    /**
     * 编辑菜单
     * @return \think\response
     */
    public function doEdit()
    {
        $requestParam = $this->getDecryptRequestParam();

        $validate = new validate();
        if (!$validate->scene('doEdit')->check($requestParam)) {
            return $this->errorResponse($validate->getError());
        }

        $result = model::editMenu($requestParam);
        return $this->jsonResponse($result);
    }
}