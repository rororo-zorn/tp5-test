<?php

namespace app\common\model;

abstract class BackendModel extends BaseModel
{
    /**
     * 类型自动转换
     * @var string[]
     */
    protected $type = [
        'add_time' => 'timestamp',
    ];
}