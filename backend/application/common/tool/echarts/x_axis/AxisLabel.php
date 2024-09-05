<?php

namespace app\common\tool\echarts\x_axis;

/**
 * Class AxisLabel
 * 坐标轴刻度标签的相关设置
 * @package app\index\tool\echarts\option\x_axis
 */
class AxisLabel
{
    /**
     * 坐标轴刻度标签默认显示间隔
     */
    const INTERVAL_DEFAULT = 'auto';

    /**
     * 标签默认旋转角度
     */
    const ROTATE_DEFAULT = 0;

    /**
     * 是否显示刻度标签，默认显示
     * @var bool
     */
    public $show = true;

    /**
     * 坐标轴刻度标签的显示间隔
     * 默认为auto，设置0强制显示所有标签
     * @var
     */
    public $interval;

    /**
     * 刻度标签旋转的角度，默认为0
     * 旋转角度范围-90 ~ 90度
     * @var
     */
    public $rotate;

    /**
     * 初始化
     * AxisLabel constructor.
     * @param string $interval
     * @param int $rotate
     */
    public function __construct($interval, $rotate)
    {
        $this->interval = $interval;
        $this->rotate = $rotate;
    }
}