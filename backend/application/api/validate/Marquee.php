<?php

namespace app\api\validate;

use app\common\validate\BaseValidate;

class Marquee extends BaseValidate
{
    /**
     * 定义验证规则
     */
    protected $rule = [
        'id' => ['require', 'number'],
    ];

    /**
     * 定义错误信息
     */
    protected $message = [
        'id.require' => '非法参数',
        'id.number' => '非法参数',
    ];
}
