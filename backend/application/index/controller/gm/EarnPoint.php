<?php

namespace app\index\controller\gm;

use app\common\controller\BaseController;
use app\index\model\gm\EarnPoint as model;
use app\index\validate\gm\EarnPoint as validate;

class EarnPoint extends BaseController
{
    /**
     * 显示配置
     * @return mixed
     */
    public function index()
    {
        return $this->fetch('gm/earn_point/index', ['tableClos' => model::getLayUiTableClos()]);
    }

    /**
     * 显示本周配置
     * @return \think\response
     */
    public function showThisWeekConfig()
    {
        $result = (new model())->getThisWeekConfigTableData();
        return $this->returnToLayuiWithoutPage($result);
    }

    /**
     * 显示下周配置
     * @return \think\response
     */
    public function showNextWeekConfig()
    {
        $result = (new model())->getNextWeekConfigTableData();
        return $this->returnToLayuiWithoutPage($result);
    }

    /**
     * 添加本周配置
     * @return mixed
     */
    public function addThisWeekConfig()
    {
        return $this->fetch('gm/earn_point/add_this_week_config', [
            'date' => model::getEffectiveDate(),
            'game' => model::getGameType(),
        ]);
    }

    /**
     * 添加本周配置
     * @return \think\Response
     */
    public function doAddThisWeekConfig()
    {
        $requestParam = $this->getFormatRequestParam();
        $requestParam['add_this_week_config'] = true;

        $validate = new validate();
        if (!$validate->scene('doAddThisWeekConfig')->check($requestParam)) {
            return $this->errorResponse($validate->getError());
        }

        $result = (new model())->setThisWeekConfig($requestParam['config']);
        return $this->jsonResponse($result);
    }

    /**
     * 编辑本周配置
     * @return mixed
     */
    public function editThisWeekConfig()
    {
        $config = (new model())->getThisWeekConfig();
        if (empty($config)) {
            return $this->errorResponse();
        }

        return $this->fetch('gm/earn_point/edit_this_week_config', [
            'config' => $config,
            'date' => model::getEffectiveDate(),
            'game' => model::getGameType(),
        ]);
    }

    /**
     * 编辑本周配置
     * @return \think\Response
     */
    public function doEditThisWeekConfig()
    {
        $requestParam = $this->getFormatRequestParam();

        $validate = new validate();
        if (!$validate->scene('doEditThisWeekConfig')->check($requestParam)) {
            return $this->errorResponse($validate->getError());
        }

        $result = (new model())->setThisWeekConfig($requestParam['config']);
        return $this->jsonResponse($result);
    }


    /**
     * 添加下周配置
     * @return mixed
     */
    public function addNextWeekConfig()
    {
        return $this->fetch('gm/earn_point/add_next_week_config', [
            'date' => model::getEffectiveDate(),
            'game' => model::getGameType(),
        ]);
    }

    /**
     * 添加下周配置
     * @return \think\Response
     */
    public function doAddNextWeekConfig()
    {
        $requestParam = $this->getFormatRequestParam();
        $requestParam['add_next_week_config'] = true;

        $validate = new validate();
        if (!$validate->scene('doAddNextWeekConfig')->check($requestParam)) {
            return $this->errorResponse($validate->getError());
        }

        $result = (new model())->setNextWeekConfig($requestParam['config']);
        return $this->jsonResponse($result);
    }

    /**
     * 编辑下周配置
     * @return mixed
     */
    public function editNextWeekConfig()
    {
        $config = (new model())->getNextWeekConfig();
        if (empty($config)) {
            return $this->errorResponse();
        }

        return $this->fetch('gm/earn_point/edit_next_week_config', [
            'config' => $config,
            'date' => model::getEffectiveDate(),
            'game' => model::getGameType(),
        ]);
    }

    /**
     * 编辑下周配置
     * @return \think\Response
     */
    public function doEditNextWeekConfig()
    {
        $requestParam = $this->getFormatRequestParam();

        $validate = new validate();
        if (!$validate->scene('doEditNextWeekConfig')->check($requestParam)) {
            return $this->errorResponse($validate->getError());
        }

        $result = (new model())->setNextWeekConfig($requestParam['config']);
        return $this->jsonResponse($result);
    }

    /**
     * 获取规范后的请求参数
     * @return array|mixed|null
     */
    protected function getFormatRequestParam()
    {
        $requestParam = $this->request->param();
        array_walk_recursive($requestParam, function (&$item) {
            if (is_numeric($item)) {
                $item = intval($item);
            }
        });

        foreach (model::getEffectiveDate() as $key => $date) {
            if (isset($requestParam['config'][$key])) {
                $requestParam['config'][$key] = array_values($requestParam['config'][$key]);
            } else {
                $requestParam['config'][$key] = [];
            }
        }

        ksort($requestParam['config']);

        return $requestParam;
    }
}
