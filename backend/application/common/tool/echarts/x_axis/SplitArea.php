<?php

namespace app\common\tool\echarts\x_axis;

/**
 * 坐标轴在grid区域中的分割区域
 * Class SplitArea
 * @package app\index\tool\echarts\option\x_axis
 */
class SplitArea
{
    /**
     * 是否显示分割区域，默认不显示
     * @var bool
     */
    public $show;

    /**
     * 初始化
     * SplitArea constructor.
     * @param false $show
     */
    public function __construct($show = false)
    {
        $this->show = $show;
    }
}