<?php

namespace app\index\controller\gm;

use app\common\controller\BaseController;
use app\common\model\game\Notice as model;
use app\index\validate\gm\Notice as validate;

class Notice extends BaseController
{
    /**
     * 显示公告
     * @return mixed|\think\response
     */
    public function index()
    {
        if (!$this->request->isAjax()) {
            return $this->fetch('gm/notice/index', [
                'noticeType' => model::getNoticeType(),
                'showType' => model::getShowType(),
                'tableClos' => model::getLayUiTableClos(),
            ]);
        }

        $paginator = model::getPaginationData($this->request->param());
        return $this->returnToLayui($paginator);
    }

    /**
     * 添加公告
     * @return mixed
     */
    public function add()
    {
        return $this->fetch('gm/notice/add', ['model' => new model()]);
    }

    /**
     * 添加图片
     * @return mixed
     */
    public function addPicture()
    {
        return $this->fetch('gm/notice/add_picture', ['jumpId' => model::getJumpId()]);
    }

    /**
     * 添加公告
     * @return \think\Response
     * @throws \think\exception\PDOException
     */
    public function doAdd()
    {
        $requestParam = $this->getPreprocessedRequestParam();

        $validate = new validate();
        if (!$validate->scene('add')->check($requestParam)) {
            return $this->errorResponse($validate->getError());
        }

        $result = (new model())->addNotice($requestParam);
        return $this->jsonResponse($result);
    }

    /**
     * 获取预处理后的请求参数
     * @return array|mixed|null
     */
    protected function getPreprocessedRequestParam()
    {
        $requestParam = $this->getDecryptRequestParam();
        $requestParam['theme'] = $this->getPreprocessedItem($requestParam['theme']);
        if (model::isOnlyWorldNotice($requestParam['show_type'])) {
            $requestParam['title'] = $this->getPreprocessedItem($requestParam['title']);
            $requestParam['tail'] = $this->getPreprocessedItem($requestParam['tail']);
            $requestParam['content'] = $this->getPreprocessedItem($requestParam['content']);
        } else {
            $requestParam['title'] = [];
            $requestParam['tail'] = [];
            $requestParam['content'] = isset($requestParam['picture']) ? $this->getPreprocessedItem($requestParam['picture']) : [];
        }

        return $requestParam;
    }

    /**
     * 获取预处理后的数据
     * @param $item
     * @return mixed
     */
    protected function getPreprocessedItem($item)
    {
        foreach ($item as $id => $value) {
            if (empty($value)) {
                unset($item[$id]);
            }
        }

        return $item;
    }

    /**
     * 编辑公告
     * @return mixed|\think\response
     */
    public function edit()
    {
        $requestParam = $this->getDecryptRequestParam();

        $validate = new validate();
        if (!$validate->scene('edit')->check($requestParam)) {
            return $this->errorResponse($validate->getError());
        }

        $model = model::getEditNotice($requestParam['id']);
        return $model ? $this->fetch('gm/notice/edit', ['model' => $model]) : $this->errorResponse();
    }

    /**
     * 编辑公告
     * @return \think\Response
     * @throws \think\exception\PDOException
     */
    public function doEdit()
    {
        $requestParam = $this->getPreprocessedRequestParam();

        $validate = new validate();
        if (!$validate->scene('doEdit')->check($requestParam)) {
            return $this->errorResponse($validate->getError());
        }

        $result = model::editNotice($requestParam);
        return $this->jsonResponse($result);
    }

    /**
     * 删除公告
     * @return \think\Response
     * @throws \Exception
     */
    public function doDelete()
    {
        $requestParam = $this->getDecryptRequestParam();

        $validate = new validate();
        if (!$validate->scene('doDelete')->check($requestParam)) {
            return $this->errorResponse($validate->getError());
        }

        $result = model::deleteNotice($requestParam['id']);
        return $this->jsonResponse($result);
    }
}
