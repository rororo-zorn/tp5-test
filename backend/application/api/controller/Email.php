<?php

namespace app\api\controller;

use app\common\controller\CoreController;
use app\api\validate\Email as validate;
use app\common\model\backend\gm\Email as model;

class Email extends CoreController
{
    public function index()
    {
        $requestParam = $this->request->param();

        $validate = new validate();
        if (!$validate->check($requestParam)) {
            return $this->errorResponse($validate->getError());
        }

        $result = model::sendEmailToServer($requestParam['id']);
        return $this->jsonResponse($result);
    }
}