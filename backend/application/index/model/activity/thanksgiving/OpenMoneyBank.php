<?php

namespace app\index\model\activity\thanksgiving;

use app\index\model\activity\Thanksgiving;
use app\index\validate\activity\Thanksgiving as validate;

class OpenMoneyBank extends Thanksgiving
{
    protected $type = 7;

    /**
     * 验证场景
     * @var array
     */
    public $validateScent = [
        self::ACTIVITY_OPEN => validate::OPEN_MONEY_BANK_SCENE,
        self::ACTIVITY_CLOSE => 'close',
    ];
}