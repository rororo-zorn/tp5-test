<?php

namespace app\index\controller\gm;

use app\common\controller\BaseController;
use app\index\validate\gm\GameSwitch as validate;
use app\index\model\gm\GameSwitch as model;

class GameSwitch extends BaseController
{
    /**
     * 显示开关
     * @param $model
     * @return mixed
     */
    protected function showSwitch($model)
    {
        $m = model::getModel($model);

        return $this->fetch('gm/game_switch/show_switch', [
            'title' => $m::TITLE,
            'stateOn' => $m::STATE_ON,
            'tableClos' => $m::getLayUiTableClos(),
            'getUrl' => url(sprintf('index/gm.GameSwitch/get%sSwitch', $model)),
            'openUrl' => url(sprintf('index/gm.GameSwitch/open%sSwitch', $model)),
            'closeUrl' => url(sprintf('index/gm.GameSwitch/close%sSwitch', $model)),
        ]);
    }

    /**
     * 获取开关
     * @param $model
     * @return \think\response
     */
    protected function getSwitch($model)
    {
        $model = model::getModel($model);
        $data = (new $model())->getSwitch();
        return $this->returnToLayuiWithoutPage($data);
    }

    /**
     * 开启开关
     * @param $model
     * @return \think\Response
     */
    protected function openSwitch($model)
    {
        $requestParam = $this->request->param();
        $requestParam['state'] = model::STATE_ON;

        $validate = new validate();
        $model = model::getModel($model);
        $model = new $model();
        if (!$validate->setModel($model)->check($requestParam)) {
            return $this->errorResponse($validate->getError());
        }

        $result = model::openSwitch($requestParam['id']);
        return $this->jsonResponse($result);
    }

    /**
     * 关闭开关
     * @param $model
     * @return \think\Response
     */
    protected function closeSwitch($model)
    {
        $requestParam = $this->request->param();
        $requestParam['state'] = model::STATE_OFF;

        $validate = new validate();
        $model = model::getModel($model);
        $model = new $model();
        if (!$validate->setModel($model)->check($requestParam)) {
            return $this->errorResponse($validate->getError());
        }

        $result = model::closeSwitch($requestParam['id']);
        return $this->jsonResponse($result);
    }

    /**
     * 空操作实现处理请求
     * @return mixed|\think\response
     */
    public function _empty()
    {
        list($action, $model) = $this->getActionAndModel();
        $function = sprintf('%sSwitch', $action);
        return $this->{$function}($model);
    }

    /**
     * 获取动作和模型名
     * @return array
     */
    protected function getActionAndModel()
    {
        $action = $this->request->action(true);

        $actionPrefix = ['show', 'get', 'open', 'close'];
        foreach ($actionPrefix as $prefix) {
            $pl = strlen($prefix);
            if (strncmp($action, $prefix, $pl) === 0) {
                $model = str_replace('Switch', '', substr($action, $pl));
                $action = $prefix;
                break;
            }
        }

        return [$action, $model];
    }
}
