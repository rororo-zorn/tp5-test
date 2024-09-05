<?php

namespace app\index\controller;

use app\common\controller\CoreController;
use app\index\validate\Login as validate;
use think\facade\Config;
use think\facade\Session;

class Login extends CoreController
{
    /**
     * 主页url
     * @var string
     */
    protected $homeUrl = 'index/Index/index';

    /**
     * 登录页
     * @return mixed
     */
    public function index()
    {
        return $this->fetch('', ['appName' => Config::get('app.app_name')]);
    }

    /**
     * 登录操作
     * @return \think\Response
     */
    public function doLogin()
    {
        $validate = new validate();
        if (!$validate->check($this->request->param())) {
            return $this->errorResponse($validate->getError());
        }

        Session::set('session_id', $validate->getUser()->getId());
        Session::set('session_time', time());

        return $this->successResponse(url($this->homeUrl), '登录成功，正在跳转~');
    }
}