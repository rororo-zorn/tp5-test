<?php

namespace app\api\controller;

use app\common\controller\CoreController;
use app\api\validate\Email as validate;
use app\common\model\backend\gm\Marquee as model;

class Marquee extends CoreController
{
    public function index()
    {
        $requestParam = $this->request->param();

        $validate = new validate();
        if (!$validate->check($requestParam)) {
            return $this->errorResponse($validate->getError());
        }

       $result = model::sendMarqueeToServer($requestParam['id']);
        return $this->jsonResponse($result);
    }
}
