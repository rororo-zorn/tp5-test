<?php

namespace app\index\controller\gm;

use app\common\controller\BaseController;
use app\index\model\gm\LoginNotice as model;
use app\index\validate\gm\LoginNotice as validate;

class LoginNotice extends BaseController
{
    /**
     * 显示公告
     * @return mixed|\think\response
     */
    public function index()
    {
        return $this->fetch('gm/login_notice/index', ['model' => new model()]);
    }

    /**
     * 添加公告
     * @return \think\Response
     */
    public function doAdd()
    {
        $requestParam = $this->request->param();

        $validate = new validate();
        if (!$validate->check($requestParam)) {
            return $this->errorResponse($validate->getError());
        }

        $result = model::addNotice($requestParam['content']);
        return $this->jsonResponse($result);
    }

    /**
     * 删除公告
     * @return \think\Response
     */
    public function doDelete()
    {
        $result = model::deleteNotice();
        return $this->jsonResponse($result);
    }
}
