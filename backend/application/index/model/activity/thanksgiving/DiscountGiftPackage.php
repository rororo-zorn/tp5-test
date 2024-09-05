<?php

namespace app\index\model\activity\thanksgiving;

use app\index\model\activity\Thanksgiving;
use app\index\validate\activity\Thanksgiving as validate;

class DiscountGiftPackage extends Thanksgiving
{
    protected $type = 3;

    /**
     * 验证场景
     * @var array
     */
    protected $validateScent = [
        self::ACTIVITY_OPEN => validate::DISCOUNT_GIFT_PACKAGE_SCENE,
        self::ACTIVITY_CLOSE => 'close',
    ];

    /**
     * 获取存储redis的数据
     * @param $requestParam
     * @return array
     */
    protected function getContent($requestParam)
    {
        return [
            'startTime' => strtotime($requestParam['startTime']),
            'endTime' => strtotime($requestParam['endTime']),
            'param' => 0,   // 默认
            'type' => $this->type,
            'goodsId' => 0, // 默认
            'operation' => self::FUNCTION_DEFAULT   // 默认
        ];
    }
}
