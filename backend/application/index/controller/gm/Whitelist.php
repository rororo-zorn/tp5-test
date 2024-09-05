<?php

namespace app\index\controller\gm;

use app\common\controller\BaseController;
use app\index\model\gm\Whitelist as model;
use app\index\validate\gm\Whitelist as validate;

class Whitelist extends BaseController
{
    /**
     * 显示白名单
     * @return mixed|\think\response
     */
    public function index()
    {
        if (!$this->request->isAjax()) {
            return $this->fetch('gm/whitelist/index', ['tableClos' => model::getLayUiTableClos()]);
        }

        $data = model::getWhitelist($this->request->param());
        return $this->returnToLayuiWithoutPage($data);
    }

    /**
     * 添加白名单
     * @return mixed
     */
    public function add()
    {
        return $this->fetch('gm/whitelist/add');
    }

    /**
     * 添加白名单
     * @return \think\response
     */
    public function doAdd()
    {
        $requestParam = $this->request->param();

        $validate = new validate();
        if (!$validate->scene('doAdd')->check($requestParam)) {
            return $this->errorResponse($validate->getError());
        }

        $result = model::addWhitelist($requestParam['uid']);
        return $this->jsonResponse($result);
    }

    /**
     * 编辑白名单
     * @return \think\response
     */
    public function doDelete()
    {
        $requestParam = $this->request->param();

        $validate = new validate();
        if (!$validate->scene('doDelete')->check($requestParam)) {
            return $this->errorResponse($validate->getError());
        }

        $result = model::deleteWhitelist($requestParam['uid']);
        return $this->jsonResponse($result);
    }
}
