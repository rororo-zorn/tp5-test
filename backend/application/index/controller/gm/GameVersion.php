<?php

namespace app\index\controller\gm;

use app\common\controller\BaseController;
use app\index\model\gm\GameVersion as model;
use app\index\validate\gm\GameVersion as validate;

class GameVersion extends BaseController
{
    /**
     * 显示版本
     * @return mixed|\think\response
     */
    public function index()
    {
        return $this->fetch('gm/game_version/index', ['version' => (new model())->getVersion()]);
    }

    /**
     * android审核服配置
     * @return \think\response
     */
    public function androidAuditConfig()
    {
        $requestParam = $this->request->param();

        $validate = new validate();
        if (!$validate->scene('androidAuditConfig')->check($requestParam)) {
            return $this->errorResponse($validate->getError());
        }

        $result = model::androidAuditConfig($requestParam['android_audit_version'] ?? '');
        return $this->jsonResponse($result);
    }

    /**
     * ios审核服配置
     * @return \think\response
     */
    public function iosAuditConfig()
    {
        $requestParam = $this->request->param();

        $validate = new validate();
        if (!$validate->scene('iosAuditConfig')->check($requestParam)) {
            return $this->errorResponse($validate->getError());
        }

        $result = model::iosAuditConfig($requestParam['ios_audit_version'] ?? '');
        return $this->jsonResponse($result);
    }

    /**
     * android正式服配置
     * @return \think\response
     */
    public function androidConfig()
    {
        $requestParam = $this->request->param();

        $validate = new validate();
        if (!$validate->scene('androidConfig')->check($requestParam)) {
            return $this->errorResponse($validate->getError());
        }

        $result = model::androidConfig($requestParam['android_version'] ?? '');
        return $this->jsonResponse($result);
    }

    /**
     * ios正式服配置
     * @return \think\response
     */
    public function iosConfig()
    {
        $requestParam = $this->request->param();

        $validate = new validate();
        if (!$validate->scene('iosConfig')->check($requestParam)) {
            return $this->errorResponse($validate->getError());
        }

        $result = model::iosConfig($requestParam['ios_version'] ?? '');
        return $this->jsonResponse($result);
    }
}
