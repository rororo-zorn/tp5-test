<?php

namespace app\index\controller\operation;

use app\common\controller\BaseController;
use app\common\model\backend\Channel as model;

class Channel extends BaseController
{
    /**
     * 显示渠道信息
     * @return mixed|\think\response
     */
    public function index()
    {
        if (!$this->request->isAjax()) {
            return $this->fetch('operation/channel/index', ['tableClos' => model::getLayUiTableClos()]);
        }

        $paginator = model::getPaginationData($this->request->param());
        return $this->returnToLayui($paginator);
    }
}
