<?php

namespace app\common\model\backend\operation\retention;

use app\common\model\backend\operation\Retention;

class ActiveRetention extends Retention
{
    /**
     * 活跃留存
     * @var int
     */
    protected $retentionType = 3;
}
