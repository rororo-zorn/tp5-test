<?php

namespace app\index\validate\gm;

use app\common\validate\BaseValidate;
use app\common\model\backend\gm\Email as model;

class Email extends BaseValidate
{
    /**
     * 定义验证规则
     */
    protected $rule = [
        'theme' => ['require', 'array', 'validateTheme'],
        'title' => ['require', 'array', 'validateTitle'],
        'content' => ['require', 'array', 'validateContent'],
        'tail' => ['require', 'array', 'validateTail'],
        'item' => ['array', 'validateItem'],
        'send_type' => ['require', 'number', 'validateSendType'],
        'uid' => 'validateUid',
        'send_time' => ['require', 'date', 'validateSendTime'],
        'token_add_email' => ['tokenRequire', 'token:token_add_email'],
        'id' => ['require', 'number'],
        'token_edit_email' => ['tokenRequire', 'token:token_edit_email'],
    ];

    /**
     * 定义错误信息
     */
    protected $message = [
        'theme.require' => '请输入邮件主题',
        'theme.array' => '邮件主题，非法参数',
        'title.require' => '请输入邮件标题',
        'title.array' => '邮件标题，非法参数',
        'content.require' => '请输入邮件内容',
        'content.array' => '邮件内容，非法参数',
        'tail.require' => '请输入邮件落款',
        'tail.array' => '邮件落款，非法参数',
        'item.array' => '附件格式错误',
        'send_type.require' => '请选择发送对象',
        'send_type.number' => '发送对象，非法参数',
        'send_time.require' => '请选择发送时间',
        'send_time.date' => '发送时间，非法参数',
        'id.require' => '非法参数',
        'id.number' => '非法参数',
    ];

    /**
     * 定义验证场景
     * @var array
     */
    protected $scene = [
        'doAdd' => ['theme', 'title', 'content', 'tail', 'item', 'send_type', 'uid', 'send_time' , 'token_add_email'],
        'showItem' => ['id'],
        'edit' => ['id'],
        'doEdit' => ['id', 'theme', 'title', 'content', 'tail', 'item', 'send_type', 'uid', 'send_time', 'token_edit_email'],
        'doDelete' => ['id'],
    ];

    /**
     * 验证主题
     * @param $value
     * @return bool|string
     */
    protected function validateTheme($value)
    {
        return $this->validateEmail('主题', $value);
    }

    /**
     * 验证标题
     * @param $value
     * @return bool|string
     */
    protected function validateTitle($value)
    {
        return $this->validateEmail('标题', $value);
    }

    /**
     * 验证内容
     * @param $value
     * @return bool|string
     */
    protected function validateContent($value)
    {
        return $this->validateEmail('内容', $value);
    }

    /**
     * 验证落款
     * @param $value
     * @return bool|string
     */
    protected function validateTail($value)
    {
        return $this->validateEmail('落款', $value);
    }

    /**
     * 验证邮件
     * @param $type
     * @param $value
     * @return bool|string
     */
    protected function validateEmail($type, $value)
    {
        $languageList = model::getLanguageList();

        if (!empty(array_diff_key($value, $languageList))) {
            return  $type . '，非法参数';
        }

        foreach ($languageList as $lang => $item) {
            if (empty($value[$lang])) {
                return '请输入' . $item['name'] . $type;
            }
        }

        return true;
    }

    /**
     * 是否合法附件
     * @param $value
     * @return bool|string
     */
    protected function validateItem($value)
    {
        foreach ($value as $item) {
            if (!in_array($item['id'], array_keys(model::getItem()))) {
                return '附件ID（' . $item['id'] . '），非法参数';
            }

            if ($item['amount'] <= 0) {
                return '附件数量必须大于0';
            }
        }

        return true;
    }

    /**
     * 是否合法发送对象
     * @param $value
     * @return bool|string
     */
    protected function validateSendType($value)
    {
        return in_array($value, array_keys(model::getSendType())) ?: '发送对象，非法参数';
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
     * 是否合法发送时间
     * @param $value
     * @return bool|string
     */
    protected function validateSendTime($value)
    {
        return strtotime($value) > time() ?: '发送时间必须大于当前时间（' . date('Y-m-d H:i:s') . '）';
    }
}
