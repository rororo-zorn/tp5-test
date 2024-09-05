<?php

namespace app\index\controller\operation;

use app\common\controller\BaseController;
use app\common\model\backend\Platform as model;
use app\index\validate\operation\Platform as validate;

class Platform extends BaseController
{
    /**
     * 显示平台信息
     * @return mixed|\think\response
     */
    public function index()
    {
        if (!$this->request->isAjax()) {
            return $this->fetch('operation/platform/index', ['tableClos' => model::getLayUiTableClos()]);
        }

        $paginator = model::getPaginationData($this->request->param());
        return $this->returnToLayui($paginator);
    }

    /**
     * 编辑平台
     * @return mixed
     */
    public function edit()
    {
        $requestParam = $this->request->param();

        $validate = new validate();
        if (!$validate->scene('edit')->check($requestParam)) {
            return $this->errorResponse($validate->getError());
        }

        $model = model::getEditPlatform($requestParam['id']);
        return $model ? $this->fetch('operation/platform/edit', ['model' => $model]) : $this->errorResponse();
    }

    /**
     * 编辑平台
     * @return \think\Response
     */
    public function doEdit()
    {
        $requestParam = $this->request->param();

        $validate = new validate();
        if (!$validate->scene('doEdit')->check($requestParam)) {
            return $this->errorResponse($validate->getError());
        }

        $result = model::editPlatform($requestParam);
        return $this->jsonResponse($result);
    }
}
