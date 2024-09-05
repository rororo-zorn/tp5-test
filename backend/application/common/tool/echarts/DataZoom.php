<?php

namespace app\common\tool\echarts;

/**
 * 区域缩放（目前固定为x轴）
 * Class DataZoom
 * @package app\index\tool\echarts
 */
class DataZoom
{
    /**
     * 内置型
     */
    const TYPE_INSIDE = 'inside';

    /**
     * 滑动条型
     */
    const TYPE_SLIDER = 'slider';

    /**
     * 数据窗口范围起始百分比默认为0
     */
    const START_DEFAULT = 0;

    /**
     * 数据窗口范围结束百分比默认为10
     */
    const END_DEFAULT = 10;

    /**
     * 类型（inside：内置型 slider：滑动条型）
     * @var
     */
    public $type;

//    /**
//     * 拖动时，是否实时更新系列的视图
//     * 滑动条型独有属性，默认开启（true）
//     * @var
//     */
//    public $realtime;

    /**
     * 默认控制x轴
     * @var int
     */
    public $xAxisIndex = 0;

    /**
     * 数据过滤模式，默认为filter
     * @var string
     */
    public $filterMode = 'filter';

    /**
     * 数据窗口范围的起始百分比
     * @var int
     */
    public $start;

    /**
     * 数据窗口范围的结束百分比
     * @var int
     */
    public $end;

    /**
     * 布局方式，默认为horizontal（水平）
     * @var string
     */
    public $orient = 'horizontal';

    /**
     * 初始化
     * DataZoom constructor.
     * @param $type
     * @param int $start
     * @param int $end
     */
    public function __construct($type, $start = self::START_DEFAULT, $end = self::END_DEFAULT)
    {
        $this->type = $type;
        $this->start = $start;
        $this->end = $end;
    }
}