<?php

namespace app\index\validate\gm;

use app\common\validate\BaseValidate;
use app\common\model\backend\gm\Marquee as model;

class Marquee extends BaseValidate
{
    /**
     * 备注信息长度限制
     * @var int
     */
    private $remarkLengthLimit = 64;

    /**
     * 定义验证规则
     */
    protected $rule = [
        'marquee_type' => ['require', 'number', 'validateMarqueeType'],
        'content' => 'require',
        'start_time' => ['require', 'date'],
        'end_time' => ['require', 'date', 'validateEndTime'],
        'broadcast_start_time' => ['require', 'date'],
        'broadcast_interval' => ['require', 'number'],
        'broadcast_times' => ['require', 'number', 'validateBroadcastTimes'],
        'remark' => 'validateRemark',
        'token_add_marquee' => ['tokenRequire', 'token:token_add_marquee'],
        'id' => ['require', 'number'],
        'token_edit_marquee' => ['tokenRequire', 'token:token_edit_marquee'],
    ];

    /**
     * 定义错误信息
     */
    protected $message = [
        'marquee_type.require' => '请选择跑马灯类型',
        'marquee_type.number' => '跑马灯类型，非法参数',
        'content.require' => '请输入跑马灯内容',
        'start_time.require' => '请输入开始时间',
        'start_time.date' => '开始时间，非法参数',
        'end_time.require' => '请输入截止时间',
        'end_time.date' => '截止时间，非法参数',
        'broadcast_start_time.require' => '请输入播报开始时间',
        'broadcast_start_time.date' => '播报开始时间，非法参数',
        'broadcast_interval.require' => '请输入播报间隔',
        'broadcast_interval.number' => '播报间隔，非法参数',
        'broadcast_times.require' => '请输入播报次数',
        'broadcast_times.number' => '播报次数，非法参数',
        'id.require' => '非法参数',
        'id.number' => '非法参数',
    ];

    /**
     * 定义验证场景
     * @var array
     */
    protected $scene = [
        'add' => ['marquee_type', 'content', 'start_time', 'end_time', 'broadcast_start_time', 'broadcast_interval',
            'broadcast_times', 'remark', 'token_add_marquee'],
        'edit' => ['id'],
        'doEdit' => ['id', 'marquee_type', 'content', 'start_time', 'end_time', 'broadcast_start_time', 'broadcast_interval',
            'broadcast_times', 'remark', 'token_edit_marquee'],
        'doDelete' => ['id'],
    ];

    /**
     * 验证跑马灯类型的合法性
     * @param $value
     * @return bool|string
     */
    protected function validateMarqueeType($value)
    {
        return in_array($value, array_keys(model::getMarqueeType())) ?: '跑马灯类型，非法参数';
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
        return strtotime($value) >= strtotime($data['start_time']) ?: '截止时间必须大于或等于开始时间';
    }

    /**
     * 验证播报次数的合法性
     * @param $value
     * @param $rule
     * @param $data
     * @return bool|string
     */
    protected function validateBroadcastTimes($value, $rule, $data)
    {
        if ($data['broadcast_interval'] > 0) {
            return $value > 0 ?: '播报次数必须大于0次';
        }

        return true;
    }

    /**
     * 验证备注信息的合法性
     * @param $value
     * @return bool|string
     */
    protected function validateRemark($value)
    {
        return mb_strlen($value) <= $this->remarkLengthLimit ?: '备注信息必须在64个字符以内';
    }
}
