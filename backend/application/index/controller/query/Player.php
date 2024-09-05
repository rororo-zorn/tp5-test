<?php

namespace app\index\controller\query;

use app\common\controller\BaseController;
use app\common\model\game\User as model;

class Player extends BaseController
{
    /**
     * 玩家账号查询
     * @return mixed|\think\response
     */
    public function index()
    {
        if (!$this->request->isAjax()) {
            return $this->fetch('query/player/index', [
                'tableClos' => model::getLayUiTableClos()
            ]);
        }

        $paginator = model::getPaginationData($this->request->param());
        return $this->returnToLayui($paginator);
    }
}
