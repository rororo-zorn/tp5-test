<?php

namespace app\index\controller\gm;

use app\common\controller\BaseController;
use app\common\model\backend\gm\Marquee as model;
use app\index\validate\gm\Marquee as validate;

class Marquee extends BaseController
{
    /**
     * 显示跑马灯列表
     * @return mixed|\think\response
     */
    public function index()
    {
        if (!$this->request->isAjax()) {
            return $this->fetch('gm/marquee/index', ['tableClos' => model::getLayUiTableClos()]);
        }

        $paginator = model::getPaginationData($this->request->param());
        return $this->returnToLayui($paginator);
    }

    /**
     * 添加跑马灯
     * @return mixed
     */
    public function add()
    {
        return $this->fetch('gm/marquee/add', ['marqueeType' => model::getMarqueeType()]);
    }

    /**
     * 添加跑马灯
     * @return \think\response
     * @throws \think\exception\PDOException
     */
    public function doAdd()
    {
        $requestParam = $this->request->param();

        $validate = new validate();
        if (!$validate->scene('add')->check($requestParam)) {
            return $this->errorResponse($validate->getError());
        }

        $result = (new model())->addMarquee($requestParam);
        return $this->jsonResponse($result);
    }

    /**
     * 编辑跑马灯
     * @return mixed|\think\response
     */
    public function edit()
    {
        $requestParam = $this->getDecryptRequestParam();

        $validate = new validate();
        if (!$validate->scene('edit')->check($requestParam)) {
            return $this->errorResponse($validate->getError());
        }

        $model = model::getEditMarquee($requestParam['id']);
        return $model ? $this->fetch('gm/marquee/edit', ['model' => $model]) : $this->errorResponse();
    }

    /**
     * 编辑跑马灯
     * @return \think\response
     * @throws \think\exception\PDOException
     */
    public function doEdit()
    {
        $requestParam = $this->getDecryptRequestParam();

        $validate = new validate();
        if (!$validate->scene('doEdit')->check($requestParam)) {
            return $this->errorResponse($validate->getError());
        }

        $result = model::editMarquee($requestParam);
        return $this->jsonResponse($result);
    }

    /**
     * 删除跑马灯
     * @return \think\response
     * @throws \think\exception\PDOException
     */
    public function doDelete()
    {
        $requestParam = $this->getDecryptRequestParam();

        $validate = new validate();
        if (!$validate->scene('doDelete')->check($requestParam)) {
            return $this->errorResponse($validate->getError());
        }

        $result = model::deleteMarquee($requestParam['id']);
        return $this->jsonResponse($result);
    }
}
