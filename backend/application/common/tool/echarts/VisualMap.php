<?php

namespace app\common\tool\echarts;

/**
 * 视觉映射
 * Class VisualMap
 * @package app\index\tool\echarts
 */
class VisualMap
{
    /**
     * 类型，默认分段型
     * @var string
     */
    public $type = 'piecewise';

    /**
     * 自定义范围
     * @var
     */
    public $pieces;

    /**
     * 是否显示visualMap-piecewise组件，默认不显示（但是映射的功能还存在）
     * @var bool
     */
    public $show = false;

    /**
     * 指定数据那个维度映射到视觉元素上，默认第一维度
     * @var int
     */
    public $dimension = 0;

    /**
     * 定义在选中范围外的视觉元素
     * @var string[]
     */
    public $outOfRange = [
        'color' => 'grey',
    ];

    /**
     * 初始化
     * VisualMap constructor.
     * @param $pieces
     */
    public function __construct($pieces)
    {
        $this->pieces = $pieces;
    }
}