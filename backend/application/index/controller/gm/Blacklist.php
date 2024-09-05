<?php

namespace app\index\controller\gm;

use app\common\controller\BaseController;
use app\index\model\gm\Blacklist as model;
use app\index\validate\gm\Blacklist as validate;

class Blacklist extends BaseController
{
    /**
     * 显示黑名单
     * @return mixed|\think\response
     */
    public function index()
    {
        if (!$this->request->isAjax()) {
            return $this->fetch('gm/blacklist/index', ['tableClos' => model::getLayUiTableClos()]);
        }

        $data = model::getBlacklist($this->request->param());
        return $this->returnToLayuiWithoutPage($data);
    }

    /**
     * 添加黑名单
     * @return mixed
     */
    public function add()
    {
        return $this->fetch('gm/blacklist/add');
    }

    /**
     * 添加黑名单
     * @return \think\response
     */
    public function doAdd()
    {
        $requestParam = $this->request->param();

        $validate = new validate();
        if (!$validate->scene('doAdd')->check($requestParam)) {
            return $this->errorResponse($validate->getError());
        }

        $result = model::addBlacklist($requestParam['uid']);
        return $this->jsonResponse($result);
    }

    /**
     * 编辑黑名单
     * @return \think\response
     */
    public function doDelete()
    {
        $requestParam = $this->request->param();

        $validate = new validate();
        if (!$validate->scene('doDelete')->check($requestParam)) {
            return $this->errorResponse($validate->getError());
        }

        $result = model::deleteBlacklist($requestParam['uid']);
        return $this->jsonResponse($result);
    }
}
