<?php

namespace app\index\validate\gm;

use app\common\validate\BaseValidate;

class Blacklist extends BaseValidate
{
    /**
     * 定义验证规则
     */
    protected $rule = [
        'uid' => ['require', 'regex:uidRegex'],
        'token_add_blacklist' => ['tokenRequire', 'token:token_add_blacklist'],
    ];

    /**
     * 定义错误信息
     */
    protected $message = [
        'uid.require' => '玩家ID，非法参数',
        'uid.regex' => '玩家ID，非法参数',
    ];

    /**
     * 定义验证场景
     * @var array
     */
    protected $scene = [
        'doAdd' => ['uid', 'token_add_blacklist'],
        'doDelete' => ['uid'],
    ];
}
