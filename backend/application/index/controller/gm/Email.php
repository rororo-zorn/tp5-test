<?php

namespace app\index\controller\gm;

use app\common\controller\BaseController;
use app\common\model\backend\gm\Email as model;
use app\index\validate\gm\Email as validate;

class Email extends BaseController
{
    /**
     * 显示邮件列表
     * @return mixed|\think\response
     */
    public function index()
    {
        if (!$this->request->isAjax()) {
            return $this->fetch('gm/email/index', ['model' => new model()]);
        }

        $paginator = model::getPaginationData($this->request->param());
        return $this->returnToLayui($paginator);
    }

    /**
     * 显示附件
     * @return mixed
     */
    public function showItem()
    {
        $requestParam = $this->getDecryptRequestParam();

        $validate = new validate();
        if (!$validate->scene('showItem')->check($requestParam)) {
            return $this->errorResponse($validate->getError());
        }

        $data = model::getItemById($requestParam['id']);    // 可能返回空数组
        return $data === false ? $this->errorResponse() : $this->fetch('gm/email/show_item', ['data' => $data]);
    }

    /**
     * 添加邮件
     * @return mixed
     */
    public function add()
    {
        return $this->fetch('gm/email/add', ['model' => new model()]);
    }

    /**
     * 添加附件
     * @return mixed
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function addItem()
    {
        return $this->fetch('gm/email/add_item', ['item' => model::getItem()]);
    }

    /**
     * 添加邮件
     * @return \think\response
     * @throws \think\exception\PDOException
     */
    public function doAdd()
    {
        $requestParam = $this->getPreprocessRequestParam();

        $validate = new validate();
        if (!$validate->scene('doAdd')->check($requestParam)) {
            return $this->errorResponse($validate->getError());
        }

        $result = (new model())->addEmail($requestParam);
        return $this->jsonResponse($result);
    }

    /**
     * 获取预处理后的请求参数
     * @return array|mixed|null
     */
    protected function getPreprocessRequestParam()
    {
        $requestParam = $this->getDecryptRequestParam();
        $requestParam['item'] = isset($requestParam['item']) ? $this->getItem($requestParam['item']) : [];
        return $requestParam;
    }

    /**
     * 获取附件
     * @param $data
     * @return array
     */
    private function getItem($data)
    {
        $item = [];
        foreach ($data as $key => $value) {
            $item[] = [
                'id' => intval($value['id']),
                'amount' => intval($value['amount']),
            ];
        }

        return $item;
    }

    /**
     * 编辑邮件
     * @return mixed|\think\response
     */
    public function edit()
    {
        $requestParam = $this->getDecryptRequestParam();

        $validate = new validate();
        if (!$validate->scene('edit')->check($requestParam)) {
            return $this->errorResponse($validate->getError());
        }

        $model = model::getEditEmail($requestParam['id']);
        return $model ? $this->fetch('gm/email/edit', ['model' => $model]) : $this->errorResponse();
    }

    /**
     * 编辑邮件
     * @return \think\response
     * @throws \think\exception\PDOException
     */
    public function doEdit()
    {
        $requestParam = $this->getPreprocessRequestParam();

        $validate = new validate();
        if (!$validate->scene('doEdit')->check($requestParam)) {
            return $this->errorResponse($validate->getError());
        }

        $result = model::editEmail($requestParam);
        return $this->jsonResponse($result);
    }

    /**
     * 删除邮件
     * @return \think\response
     * @throws \think\exception\PDOException
     */
    public function doDelete()
    {
        $requestParam = $this->getDecryptRequestParam();

        $validate = new validate();
        if (!$validate->scene('doDelete')->check($requestParam)) {
            return $this->errorResponse($validate->getError());
        }

        $result = model::deleteEmail($requestParam['id']);
        return $this->jsonResponse($result);
    }
}
