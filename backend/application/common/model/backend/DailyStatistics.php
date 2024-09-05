<?php

namespace app\common\model\backend;

use app\common\model\BackendModel;

class DailyStatistics extends BackendModel
{
    /**
     * 类型自动转换
     * @var string[]
     */
    protected $type = [
        'new_user' => 'json',
        'active_user' => 'json',
        'paid_user' => 'json',
        'bankruptcy_user' => 'json',
    ];
}