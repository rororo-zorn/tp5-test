<?php

namespace app\common\tool\traits;

trait GoldGrade
{
    /**
     * 获取金币档次
     * @return string[]
     */
    public static function getGoldGrade()
    {
        return [
            1 => '档次1',
            2 => '档次2',
            3 => '档次3',
            4 => '档次4',
        ];
    }
}