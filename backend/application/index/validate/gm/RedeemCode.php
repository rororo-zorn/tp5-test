<?php

namespace app\index\validate\gm;

use app\common\validate\BaseValidate;
use app\common\model\game\Coupon as model;

class RedeemCode extends BaseValidate
{
    /**
     * 定义验证规则
     */
    protected $rule = [
        'id' => ['require'],
        'type' => ['require', 'validateType'],
        'count' => ['require', 'number', 'gt:0'],
        'item' => ['require', 'array', 'validateItem'],
        'startTime' => ['require', 'date', 'dateFormat:Y-m-d H:i:s'],
        'endTime' => ['require', 'date', 'dateFormat:Y-m-d H:i:s', 'validateEndTime'],
        'token_add_redeem_code' => ['tokenRequire', 'token:token_add_redeem_code'],
    ];

    /**
     * 定义错误信息
     */
    protected $message = [
        'id.require' => '非法参数',
        'type.require' => '请选择领取类型',
        'count.require' => '请输入兑换码数量',
        'count.number' => '兑换码数量，非法参数',
        'count.gt' => '兑换码数量必须大于0',
        'item.require' => '请配置兑换奖励',
        'item.array' => '兑换奖励，非法参数',
        'startTime.require' => '请输入开始时间',
        'startTime.date' => '开始时间，非法格式',
        'startTime.dateFormat' => '开始时间，非法格式',
        'endTime.require' => '截止时间，非法格式',
        'endTime.date' => '截止时间，非法格式',
        'endTime.dateFormat' => '截止时间，非法格式',
    ];

    /**
     * 定义验证场景
     * @var array
     */
    protected $scene = [
        'showItem' => ['id'],
        'doAdd' => ['type', 'count', 'item', 'startTime', 'endTime', 'token_add_redeem_code'],
    ];

    /**
     * 验证领取类型
     * @param $value
     * @return bool|string
     */
    protected function validateType($value)
    {
        return in_array($value, array_keys(model::getExchangeType())) ?: '领取类型，非法参数';
    }

    /**
     * 验证兑换奖励
     * @param $value
     * @return bool|string
     */
    protected function validateItem($value)
    {
        foreach ($value as $item) {
            if (!in_array($item['id'], array_keys(model::getItem()))) {
                return '奖励ID（' . $item['id'] . '），非法参数';
            }

            if ($item['amount'] <= 0) {
                return '奖励数量必须大于0';
            }
        }

        return true;
    }


    /**
     * 验证截止时间是否大于开始时间
     * @param $value
     * @param $rule
     * @param $data
     * @return bool|string
     */
    protected function validateEndTime($value, $rule, $data)
    {
        $startTime = strtotime($data['startTime']);
        return strtotime($value) > $startTime ?: '截止时间必须大于开始时间';
    }
}
