<?php

namespace app\index\controller;

use app\common\controller\BaseController;
use think\facade\Config;
use app\common\tool\facade\FacadeUser;
use app\index\validate\Index as validate;

class Index extends BaseController
{
    /**
     * 登录url
     * @var string
     */
    protected $loginUrl = 'index/Login/index';

    /**
     * 主页
     * @return mixed
     */
    public function index()
    {
        return $this->fetch('', [
            'appName' => Config::get('app.app_name'),
            'menu' => FacadeUser::getPrivilege(),
            'username' => FacadeUser::getUserUsername(),
        ]);
    }

    /**
     * 欢迎页
     * @return mixed
     */
    public function welcome()
    {
        return $this->fetch();
    }

    /**
     * 修改密码
     * @return mixed
     */
    public function editPassword()
    {
        return $this->fetch();
    }

    /**
     * 修改密码操作
     * @return \think\Response
     */
    public function doEditPassword()
    {
        $requestParam = $this->request->param();

        $validate = new validate();
        if (!$validate->check($requestParam)) {
            return $this->errorResponse($validate->getError());
        }

        $result = FacadeUser::updatePassword($requestParam['new_password']);
        if ($result === false) {
            return $this->errorResponse();
        }

        FacadeUser::clearSession();

        return $this->successResponse();
    }

    /**
     * 退出登录操作
     */
    public function doLogout()
    {
        FacadeUser::clearSession();
        $this->redirect($this->loginUrl);
    }
}
