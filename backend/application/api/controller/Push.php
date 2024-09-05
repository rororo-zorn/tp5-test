<?php

namespace app\api\controller;

use app\common\controller\CoreController;
use app\api\validate\Push as validate;
use app\common\model\backend\gm\GamePush as model;

class Push extends CoreController
{
    public function index()
    {
        $requestParam = $this->request->param();

        $validate = new validate();
        if (!$validate->check($requestParam)) {
            return $this->errorResponse($validate->getError());
        }

        $result = model::sendGamePushToServer($requestParam['id']);
        return $this->jsonResponse($result);
    }
}