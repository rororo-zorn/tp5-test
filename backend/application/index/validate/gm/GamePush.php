<?php

namespace app\index\validate\gm;

use app\common\validate\BaseValidate;
use app\common\model\backend\gm\GamePush as model;

class GamePush extends BaseValidate
{
    /**
     * 定义验证规则
     */
    protected $rule = [
        'push_type' => ['require', 'number', 'validatePushType'],
        'send_type' => ['require', 'number', 'validateSendType'],
        'uid' => 'validateUid',
        'title' => ['require', 'max:500'],
        'content' => ['require', 'max:1000'],
        'image' => ['url', 'max:500'],
        'start_time' => ['require', 'date'],
        'end_time' => ['require', 'date', 'validateEndTime'],
        'push_start_time' => ['require', 'date'],
        'push_interval' => ['require', 'number', 'gt:0'],
        'push_interval_unit' => ['require', 'number', 'validatePushIntervalUnit'],
        'push_times' => ['require', 'number', 'gt:0'],
        'token_add_game_push' => ['tokenRequire', 'token:token_add_game_push'],
        'id' => ['require', 'number'],
        'token_edit_game_push' => ['tokenRequire', 'token:token_edit_game_push'],
    ];

    /**
     * 定义错误信息
     */
    protected $message = [
        'push_type.require' => '请选择推送类型',
        'push_type.number' => '推送类型，非法参数',
        'send_type.require' => '请选择发送对象',
        'send_type.number' => '发送对象，非法参数',
        'title.require' => '请输入推送标题',
        'title.max' => '推送标题最大长度为500字符',
        'content.require' => '请输入推送内容',
        'content.max' => '推送内容最大长度为1000字符',
        'image.url' => '请输入合法的推送图片地址',
        'image.max' => '推送图片地址最大长度为500字符',
        'start_time.require' => '请输入开始时间',
        'start_time.date' => '开始时间，非法参数',
        'end_time.require' => '请输入截止时间',
        'end_time.date' => '截止时间，非法参数',
        'push_start_time.require' => '请输入开始时间',
        'push_start_time.date' => '开始时间，非法参数',
        'push_interval.require' => '请输入推送间隔',
        'push_interval.number' => '推送间隔，非法参数',
        'push_interval.gt' => '推送间隔必须大于0',
        'push_interval_unit.require' => '间隔单位，非法参数',
        'push_interval_unit.number' => '间隔单位，非法参数',
        'push_times.require' => '请输入推送次数',
        'push_times.number' => '推送次数，非法参数',
        'push_times.gt' => '推送次数必须大于0',
        'id.require' => '非法参数',
        'id.number' => '非法参数',
    ];

    /**
     * 定义验证场景
     * @var array
     */
    protected $scene = [
        'add' => ['push_type', 'send_type', 'uid', 'title', 'content', 'image', 'start_time', 'end_time',
            'push_start_time', 'push_interval', 'push_interval_unit', 'push_times', 'token_add_game_push'],
        'edit' => ['id'],
        'doEdit' => ['id', 'push_type', 'send_type', 'uid', 'title', 'content', 'image', 'start_time', 'end_time',
            'push_start_time', 'push_interval', 'push_interval_unit', 'push_times', 'token_edit_game_push'],
        'doDelete' => ['id'],
    ];

    /**
     * 是否合法推送类型
     * @param $value
     * @return bool|string
     */
    protected function validatePushType($value)
    {
        $type = array_keys(model::getPushType());
        return in_array($value, $type) ? true : '推送类型，非法参数';
    }

    /**
     * 是否合法发送对象
     * @param $value
     * @return bool|string
     */
    protected function validateSendType($value)
    {
        $type = array_keys(model::getSendType());
        return in_array($value, $type) ? true : '发送对象，非法参数';
    }

    /**
     * 是否合法指定uid
     * @param $value
     * @param $rule
     * @param $data
     * @return bool|string
     */
    protected function validateUid($value, $rule, $data)
    {
        $sendType = $data['send_type'];
        if ($sendType == model::SEND_TO_DESIGNATED_ID) {
            if (empty($value)) {
                return '请输入指定玩家ID';
            }

            foreach (explode_comma($value) as $key => $uid) {
                if (!$this->regex($uid, 'uidRegex')) {
                    return sprintf('第%d个玩家ID格式错误', $key + 1);
                }
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
        return strtotime($value) >= strtotime($data['start_time']) ?: '截止时间必须大于或等于开始时间';
    }

    /**
     * 验证间隔单位的合法性
     * @param $value
     * @return bool|string
     */
    protected function validatePushIntervalUnit($value)
    {
        $unit = array_keys(model::getPushIntervalUnit());
        return in_array($value, $unit) ? true : '间隔单位，非法参数';
    }
}
