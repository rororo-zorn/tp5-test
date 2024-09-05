<?php

namespace app\common\controller;

use think\Controller;
use app\common\tool\traits\JsonResponse;

class CoreController extends Controller
{
    use JsonResponse;

    /**
     * json response
     * @param $result
     * @return \think\Response
     */
    protected function jsonResponse($result)
    {
        return $result ? $this->successResponse() : $this->errorResponse();
    }
}