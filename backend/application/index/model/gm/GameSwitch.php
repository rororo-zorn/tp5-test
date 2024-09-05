<?php

namespace app\index\model\gm;

use app\common\tool\excel\read\GameModule;
use app\common\tool\redis\gm\GameSwitch as redis;
use app\common\tool\pipe\gm\GameSwitch as pipe;

abstract class GameSwitch
{
    /**
     * 关闭状态
     */
    const STATE_OFF = 0;

    /**
     * 开启状态
     */
    const STATE_ON = 1;

    /**
     * 所有配置（excel和redis配置合并）
     * @var array
     */
    protected $allSwitch = [];

    /**
     * 开关类型
     * @var
     */
    protected $switchType;

    /**
     * 是否存在主开关
     * @var bool
     */
    protected $isExistMainSwitch = false;

    /**
     * 主开关状态
     * @var bool
     */
    protected $mainSwitchState = false;

    /**
     * 开关
     * @var
     */
    protected $switch;

    public function __construct()
    {
        $this->allSwitch = $this->getAllSwitch();
        $this->initSwitch();
    }

    /**
     * layui table clos
     * @return array
     */
    public static function getLayUiTableClos()
    {
        return [
            ['field' => 'name', 'title' => '类型'],
            ['field' => 'state', 'title' => '状态', 'templet' => '#state'],
            ['field' => '', 'fixed' => 'right', 'width' => 200, 'align' => 'center', 'toolbar' => '#bar', 'title' => '操作'],
        ];
    }

    /**
     * 获取所有开关
     * @return array
     */
    protected function getAllSwitch()
    {
        $defaultSwitch = (new GameModule())->getGameModule();
        $redisSwitch = (new redis())->getAllSwitch();

        foreach ($redisSwitch as $key => $value) {
            foreach ($defaultSwitch as $k => $switch) {
                if ($switch['id'] == $key) {
                    $defaultSwitch[$k]['state'] = intval($value);
                    break;
                }
            }
        }

        return $defaultSwitch;
    }

    /**
     * 初始化开关
     */
    protected function initSwitch()
    {
        $this->initMainSwitch();

        foreach ($this->allSwitch as $switch) {
            if ($switch['type'] == $this->switchType) {
                $switch['isMainSwitch'] = $this->isMainSwitch($switch['child']);
                $switch['isExistMainSwitch'] = $this->isExistMainSwitch;
                $switch['mainSwitchState'] = $this->mainSwitchState;
                $this->switch[] = $switch;
            }
        }
    }

    /**
     * 初始化主开关
     */
    protected function initMainSwitch()
    {
        foreach ($this->allSwitch as $switch) {
            if ($switch['type'] == $this->switchType) {
                if ($this->isMainSwitch($switch['child'])) {
                    $this->isExistMainSwitch = true;
                    $this->mainSwitchState = $switch['state'];
                    break;
                }
            }
        }
    }

    /**
     * 是否为主开关
     * @param $child
     * @return bool
     */
    protected function isMainSwitch($child)
    {
        return $child == 0;
    }

    /**
     * 获取开关
     * @return mixed
     */
    public function getSwitch()
    {
        return $this->switch;
    }

    /**
     * 获取模型
     * @param $model
     * @return string
     */
    public static function getModel($model)
    {
        return sprintf('%s\\game_switch\\%s', __NAMESPACE__, $model);
    }

    /**
     * 关闭开关
     * @param $id
     * @return bool
     */
    public static function closeSwitch($id)
    {
        return (new redis())->updateSwitch($id, self::STATE_OFF) && (new pipe())->send()->isSuccess();
    }

    /**
     * 打开开关
     * @param $id
     * @return bool
     */
    public static function openSwitch($id)
    {
        return (new redis())->updateSwitch($id, self::STATE_ON) && (new pipe())->send()->isSuccess();
    }

    /**
     * 验证开关
     * @param $id
     * @param $state
     * @return bool|string
     */
    public function validateSwitch($id, $state)
    {
        $switch = $this->findSwitch($id);
        if ($switch === false) {
            return '非法参数';
        }

        if (!$this->isCanOperate($switch['child'])) {
            return '主开关已关闭，操作无效';
        }

        return $switch['state'] != $state ?? '已开启或者已关闭，请勿重复操作';
    }

    /**
     * 找到指定开关
     * @param $id
     * @return false|mixed
     */
    protected function findSwitch($id)
    {
        $find = false;
        foreach ($this->switch as $switch) {
            if ($switch['id'] == $id) {
                $find = $switch;
                break;
            }
        }

        return $find;
    }

    /**
     * 是否可操作
     * 1.不存在主开关
     * 2.当前操作的是主开关
     * 3.存在主开关且主开关打开
     * @param $id
     * @return bool
     */
    protected function isCanOperate($id)
    {
        return !$this->isExistMainSwitch || $this->isMainSwitch($id) || ($this->isExistMainSwitch && $this->mainSwitchState);
    }
}