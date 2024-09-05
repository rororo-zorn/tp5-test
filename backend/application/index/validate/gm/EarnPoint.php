<?php

namespace app\index\validate\gm;

use app\common\validate\BaseValidate;
use app\index\model\gm\EarnPoint as model;

class EarnPoint extends BaseValidate
{
    /**
     * 机台数量最小限制
     * @var int
     */
    protected $gameNumLeastLimit = 3;

    /**
     * 定义验证规则
     */
    protected $rule = [
        'add_this_week_config' => ['require', 'validateThisWeekConfig'],
        'config' => ['require', 'array', 'validateConfig'],
        'token_add_earn_point_this' => ['tokenRequire', 'token:token_add_earn_point_this'],
        'add_next_week_config' => ['require', 'validateNextWeekConfig'],
        'token_add_earn_point_next' => ['tokenRequire', 'token:token_add_earn_point_next'],
        'token_edit_earn_point_this' => ['tokenRequire', 'token:token_edit_earn_point_this'],
        'token_edit_earn_point_next' => ['tokenRequire', 'token:token_edit_earn_point_next'],
    ];

    /**
     * 定义错误信息
     */
    protected $message = [
        'add_this_week_config.require' => '非法参数',
        'config.require' => '请填写活动配置',
        'config.array' => '活动配置，非法参数',
    ];

    /**
     * 定义验证场景
     * @var array
     */
    protected $scene = [
        'doAddThisWeekConfig' => ['add_this_week_config', 'config', 'token_add_earn_point_this'],
        'doAddNextWeekConfig' => ['add_next_week_config', 'config', 'token_add_earn_point_next'],
        'doEditThisWeekConfig' => ['config', 'token_edit_earn_point_this'],
        'doEditNextWeekConfig' => ['config', 'token_edit_earn_point_next'],
    ];

    /**
     * 验证本周配置
     * @return bool|string
     */
    protected function validateThisWeekConfig()
    {
        return (new model())->isExistThisWeekConfig() ? '已存在本周配置，请勿重复添加' : true;
    }

    /**
     * 验证下周配置
     * @return bool|string
     */
    protected function validateNextWeekConfig()
    {
        return (new model())->isExistNextWeekConfig() ? '已存在下周配置，请勿重复添加' : true;
    }

    /**
     * 验证配置
     * @param $value
     * @return bool|string
     */
    protected function validateConfig($value)
    {
        $gameType = array_keys(model::getGameType());
        $effectiveDate = array_keys(model::getEffectiveDate());

        foreach ($value as $day => $config) {
            if (!in_array($day, $effectiveDate)) {
                return '活动配置，非法参数';
            }

            if (count($config) < $this->gameNumLeastLimit) {
                return '每天请至少配置3个机台';
            }

            if (!empty(array_diff($config, $gameType))) {
                return '活动配置，非法参数';
            }
        }

        return true;
    }
}
