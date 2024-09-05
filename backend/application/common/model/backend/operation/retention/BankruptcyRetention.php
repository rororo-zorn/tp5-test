<?php

namespace app\common\model\backend\operation\retention;

use app\common\model\backend\operation\Retention;

class BankruptcyRetention extends Retention
{
    /**
     * 破产留存
     * @var int
     */
    protected $retentionType = 5;
}
