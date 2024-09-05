<?php

namespace app\index\controller\gm;

use app\common\controller\BaseController;
use app\common\model\backend\gm\GamePush as model;
use app\index\validate\gm\GamePush as validate;

class GamePush extends BaseController
{
    /**
     * 显示游戏推送列表
     * @return mixed|\think\response
     */
    public function index()
    {
        if (!$this->request->isAjax()) {
            return $this->fetch('gm/game_push/index', ['tableClos' => model::getLayUiTableClos()]);
        }

        $paginator = model::getPaginationData($this->request->param());
        return $this->returnToLayui($paginator);
    }

    /**
     * 新建游戏推送
     * @return mixed
     */
    public function add()
    {
        return $this->fetch('gm/game_push/add', ['model' => new model()]);
    }

    /**
     * 新建游戏推送
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

        $result = (new model())->addGamePush($requestParam);
        return $this->jsonResponse($result);
    }

    /**
     * 编辑游戏推送
     * @return mixed|\think\response
     */
    public function edit()
    {
        $requestParam = $this->getDecryptRequestParam();

        $validate = new validate();
        if (!$validate->scene('edit')->check($requestParam)) {
            return $this->errorResponse($validate->getError());
        }

        $model = model::getEditGamePush($requestParam['id']);
        return $model ? $this->fetch('gm/game_push/edit', ['model' => $model]) : $this->errorResponse();
    }

    /**
     * 编辑游戏推送
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

        $result = model::editGamePush($requestParam);
        return $this->jsonResponse($result);
    }

    /**
     * 删除游戏推送
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

        $result = model::deleteGamePush($requestParam['id']);
        return $this->jsonResponse($result);
    }
}
