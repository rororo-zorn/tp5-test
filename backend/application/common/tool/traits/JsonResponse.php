<?php

namespace app\common\tool\traits;

use think\Response;

trait JsonResponse
{
    /**
     * 成功响应
     * @param array $data
     * @param string $msg
     * @param int $code
     * @param string $type
     * @return Response
     */
    protected function successResponse($data = [], $msg = '操作成功', $code = 0, $type = 'json')
    {
        return Response::create([
            'msg' => $msg,
            'data' => $data,
            'code' => $code,
        ], $type);
    }

    /**
     * 失败响应
     * @param string $msg
     * @param int $code
     * @param string $type
     * @return Response
     */
    protected function errorResponse($msg = '操作失败', $code = -1, $type = 'json')
    {
        return Response::create([
            'msg' => $msg,
            'code' => $code,
        ], $type);
    }
}