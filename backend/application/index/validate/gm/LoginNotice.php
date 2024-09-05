<?php

namespace app\index\validate\gm;

use app\common\validate\BaseValidate;
use app\index\model\gm\LoginNotice as model;

class LoginNotice extends BaseValidate
{
    /**
     * 定义验证规则
     */
    protected $rule = [
        'content' => ['require', 'array', 'validateContent'],
        'token_add_login_notice' => ['tokenRequire', 'token:token_add_login_notice'],
    ];

    /**
     * 定义错误信息
     */
    protected $message = [
        'content.require' => '请输入公告内容',
        'content.array' => '公告内容，非法参数',
    ];

    /**
     * 验证内容
     * @param $value
     * @return bool|string
     */
    protected function validateContent($value)
    {
        foreach (model::getLanguageList() as $lang => $item) {
            if (!isset($value[$lang])) {
                return "请输入{$item['name']}内容";
            }
        }

        return true;
    }
}
