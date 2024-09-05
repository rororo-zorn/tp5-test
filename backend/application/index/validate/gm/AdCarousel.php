<?php

namespace app\index\validate\gm;

use app\common\validate\BaseValidate;

class AdCarousel extends BaseValidate
{
    /**
     * 定义验证规则
     */
    protected $rule = [
        'id' => ['require', 'number'],
        'enable' => ['require', 'in:0,1'],
        'startTime' => ['require', 'date', 'dateFormat:Y-m-d H:i:s'],
        'endTime' => ['require', 'date', 'dateFormat:Y-m-d H:i:s', 'validateEndTime'],
        'token_edit_ad_carousel' => ['tokenRequire', 'token:token_edit_ad_carousel'],
    ];

    /**
     * 定义错误信息
     */
    protected $message = [
        'id.require' => '非法参数',
        'id.number' => '非法参数',
        'enable.require' => '状态，非法参数',
        'enable.in' => '状态，非法参数',
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
        'edit' => ['id'],
        'doEdit' => ['id', 'enable', 'startTime', 'endTime', 'token_edit_ad_carousel'],
        'doDelete' => ['id'],
    ];

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
