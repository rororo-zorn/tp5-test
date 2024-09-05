<?php

namespace app\index\validate\gm;

use app\common\validate\BaseValidate;

class GameVersion extends BaseValidate
{
    /**
     * 定义验证规则
     */
    protected $rule = [
        'token_add_android_audit_version' => ['tokenRequire', 'token:token_add_android_audit_version'],
        'token_add_ios_audit_version' => ['tokenRequire', 'token:token_add_ios_audit_version'],
        'token_add_android_version' => ['tokenRequire', 'token:token_add_android_version'],
        'token_add_ios_version' => ['tokenRequire', 'token:token_add_ios_version'],
    ];

    /**
     * 定义验证场景
     * @var array
     */
    protected $scene = [
        'androidAuditConfig' => ['token_add_android_audit_version'],
        'iosAuditConfig' => ['token_add_ios_audit_version'],
        'androidConfig' => ['token_add_android_version'],
        'iosConfig' => ['token_add_ios_version'],
    ];
}
