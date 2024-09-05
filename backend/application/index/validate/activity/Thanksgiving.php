<?php

namespace app\index\validate\activity;

use app\common\validate\BaseValidate;
use app\index\model\activity\Thanksgiving as model;

class Thanksgiving extends BaseValidate
{
    /**
     * 关闭活动验证场景
     */
    const CLOSE_ACTIVITY_SCENE = 'close';

    /**
     * 添加折扣礼包活动验证场景
     */
    const DISCOUNT_GIFT_PACKAGE_SCENE = 'discountGiftPackage';

    /**
     * 添加充值活动验证场景
     */
    const GOLD_CHARGE_SCENE = 'goldCharge';

    /**
     * 添加开启存钱罐活动验证场景
     */
    const OPEN_MONEY_BANK_SCENE = 'openMoneyBank';

    /**
     * @var model
     */
    protected $model;

    /**
     * 定义验证规则
     */
    protected $rule = [
        'isOpen' => ['require', 'validateIsOpen'],
        'startTime' => ['require', 'date'],
        'endTime' => ['require', 'date', 'validateEndTime'],
        'param' => ['require', 'number', 'gt:0'],
    ];

    /**
     * 定义错误信息
     */
    protected $message = [
        'startTime.require' => '请输入开始时间',
        'startTime.date' => '开始时间，非法参数',
        'endTime.require' => '请输入截止时间',
        'endTime.date' => '截止时间，非法参数',
        'param.require' => '请输入翻倍值',
        'param.number' => '翻倍值，非法参数',
        'param.gt' => '翻倍值必须大于0',
    ];

    /**
     * 验证场景
     * @var \string[][]
     */
    protected $scene = [
        self::CLOSE_ACTIVITY_SCENE => ['isOpen'],
        self::DISCOUNT_GIFT_PACKAGE_SCENE => ['isOpen', 'startTime', 'endTime'],
        self::GOLD_CHARGE_SCENE => ['isOpen', 'startTime', 'endTime', 'param'],
        self::OPEN_MONEY_BANK_SCENE => ['isOpen', 'startTime', 'endTime', 'param'],
    ];

    /**
     * 设置场景
     * @param $model
     * @param $isOpen
     * @return $this
     */
    public function setScene($model, $isOpen)
    {
        $this->model = $model;
        $scene = $this->model->getValidateScene($isOpen);
        $this->scene($scene);
        return $this;
    }

    /**
     * 验证开关状态
     * @param $value
     * @return bool|string
     */
    protected function validateIsOpen($value)
    {
        if ($value == $this->model::ACTIVITY_CLOSE) {
            if (!$this->model->getIsOpen()) {
                return '活动已关闭，请勿重复操作';
            }
        }

        return true;
    }

    /**
     * 验证截止时间的合法性
     * @param $value
     * @param $rule
     * @param $data
     * @return bool|string
     */
    protected function validateEndTime($value, $rule, $data)
    {
        $endTime = strtotime($value);

        if ($endTime < strtotime($data['startTime'])) {
            return '截止时间必须大于或等于开始时间';
        }

        if ($endTime <= time()) {
            return '截止时间必须大于当前时间';
        }

        return true;
    }

}
