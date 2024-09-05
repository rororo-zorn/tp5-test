<?php

namespace app\index\controller\gm;

use app\common\controller\BaseController;
use app\index\model\gm\AdCarousel as model;
use app\index\validate\gm\AdCarousel as validate;

class AdCarousel extends BaseController
{
    /**
     * 显示广告轮播图配置
     * @return mixed|\think\response
     */
    public function index()
    {
        if (!$this->request->isAjax()) {
            return $this->fetch('gm/ad_carousel/index', ['tableClos' => model::getLayUiTableClos()]);
        }

        $data = (new model())->getAdConfig();
        return $this->returnToLayuiWithoutPage($data);
    }

    /**
     * 编辑广告轮播图配置
     * @return mixed
     */
    public function edit()
    {
        $requestParam = $this->request->param();

        $validate = new validate();
        if (!$validate->scene('edit')->check($requestParam)) {
            return $this->errorResponse($validate->getError());
        }

        $data = (new model())->getAdConfigById($requestParam['id']);
        return $data ? $this->fetch('gm/ad_carousel/edit', ['data' => $data]) : $this->errorResponse();
    }

    /**
     * 编辑广告轮播图配置
     * @return \think\response
     */
    public function doEdit()
    {
        $requestParam = $this->request->param();

        $validate = new validate();
        if (!$validate->scene('doEdit')->check($requestParam)) {
            return $this->errorResponse($validate->getError());
        }

        $result = model::editAdConfig($requestParam);
        return $this->jsonResponse($result);
    }

    /**
     * 删除广告轮播图配置
     * @return \think\Response
     */
    public function doDelete()
    {
        $requestParam = $this->request->param();

        $validate = new validate();
        if (!$validate->scene('doDelete')->check($requestParam)) {
            return $this->errorResponse($validate->getError());
        }

        $result = model::deleteAdConfig($requestParam['id']);
        return $this->jsonResponse($result);
    }
}
