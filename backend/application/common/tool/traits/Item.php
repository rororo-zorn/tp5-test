<?php

namespace app\common\tool\traits;

use app\common\tool\excel\read\Item as excel;

trait Item
{
    /**
     * 物品
     * @var
     */
    protected static $item;

    /**
     * layui table clos
     * @return array
     */
    public static function getItemTableFields()
    {
        return [
            ['field' => 'id', 'title' => '物品ID'],
            ['field' => 'name', 'title' => '物品名称'],
            ['field' => 'amount', 'title' => '物品数量'],
            ['field' => '', 'fixed' => 'right', 'width' => 100, 'align' => 'center', 'toolbar' => '#bar', 'title' => '操作'],
        ];
    }

    /**
     * 获取显示附件table fields
     * @return array
     */
    protected static function getShowItemTableFields()
    {
        $tableClos = self::getItemTableFields();
        array_pop($tableClos);
        return $tableClos;
    }

    /**
     * 获取物品
     * @return array
     */
    public static function getItem()
    {
        if (self::$item == null) {
            self::$item = (new excel())->getConfig();
        }

        return self::$item;
    }
}