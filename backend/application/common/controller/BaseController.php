<?php

namespace app\common\controller;

use think\facade\Response;

class BaseController extends CoreController
{
    /**
     * 获取解密后的请求参数
     * @return array|false|mixed|string|null
     */
    protected function getDecryptRequestParam()
    {
        $request = $this->request->param();

        $decryptField = ['id', 'role_id'];
        foreach ($decryptField as $field) {
            if (isset($request[$field])) {
                $request[$field] = my_openssl_decrypt($request[$field]);
            }
        }

        return $request;
    }

    /**
     * 返回给layui table需要的数据
     * @param $paginator
     * @return \think\response
     */
    protected function returnToLayui($paginator)
    {
        return Response::create([
            'data' => $paginator->items(),
            'count' => $paginator->total(),
            'msg' => 'success',
            'code' => 0,    // layui table接口成功状态码
        ], 'json');
    }

    /**
     * 返回给layui table需要的数据
     * @param $data
     * @return \think\response
     */
    protected function returnToLayuiWithoutPage($data)
    {
        return Response::create([
            'data' => $data,
            'count' => count($data),
            'msg' => 'success',
            'code' => 0,
        ], 'json');
    }
}