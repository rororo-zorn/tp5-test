<?php

namespace app\index\controller\gm;

use app\common\controller\BaseController;
use app\index\model\gm\ServerControl as model;
use app\index\validate\gm\ServerControl as validate;

class ServerControl extends BaseController
{
    /**
     * 显示视图
     * @return mixed|\think\response
     */
    public function index()
    {
        if (!$this->request->isAjax()) {
            return $this->fetch('gm/server_control/index', [
                'tableClos' => model::getLayUiTableClos(),
                'stateOn' => model::SERVER_START,
            ]);
        }

        $data = model::getTableData();
        return $this->returnToLayuiWithoutPage($data);
    }

    /**
     * 开启服务器
     * @return \think\response
     */
    public function start()
    {
        $validate = new validate();
        if (!$validate->check(['operate' => 'start'])) {
            return $this->errorResponse($validate->getError());
        }

        $result = model::startServer();
        return $this->jsonResponse($result);
    }

    /**
     * 关闭服务器
     * @return \think\response
     */
    public function stop()
    {
        $validate = new validate();
        if (!$validate->check(['operate' => 'stop'])) {
            return $this->errorResponse($validate->getError());
        }

        $result = model::stopServer();
        return $this->jsonResponse($result);
    }
}
