<?php

namespace app\index\model\activity\thanksgiving;

use app\index\model\activity\Thanksgiving;
use app\index\validate\activity\Thanksgiving as validate;

class GoldCharge extends Thanksgiving
{
    protected $type = 2;

    /**
     * 验证场景
     * @var array
     */
    public $validateScent = [
        self::ACTIVITY_OPEN => validate::GOLD_CHARGE_SCENE,
        self::ACTIVITY_CLOSE => 'close',
    ];
}