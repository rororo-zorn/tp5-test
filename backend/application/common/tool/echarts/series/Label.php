<?php

namespace app\common\tool\echarts\series;

/**
 * 图形上的文本标签
 * Class Label
 * @package app\index\tool\echarts\option\series
 */
class Label
{
    /**
     * 标签位置定义
     */
    const POSITION_TOP = 'top';
    const POSITION_LEFT = 'left';
    const POSITION_RIGHT = 'right';
    const POSITION_BOTTOM = 'bottom';
    const POSITION_INSIDE = 'inside';

    /**
     * 距离图形元素的默认距离
     */
    const DISTANCE_DEFAULT = 5;

    /**
     * 标签默认旋转角度
     */
    const ROTATE_DEFAULT = 0;

    /**
     * 是否显示
     * @var
     */
    public $show;

    /**
     * 标签位置
     * @var string
     */
    public $position;

    /**
     * 距离图形元素的距离
     * @var
     */
    public $distance;

    /**
     * 标签旋转角度
     * @var
     */
    public $rotate;

    /**
     * 标签内容格式容
     * @var
     */
    public $formatter;

    /**
     * 初始化
     * Label constructor.
     * @param $formatter
     * @param int $rotate
     * @param int $distance
     * @param string $position
     * @param bool $show
     */
    public function __construct($formatter, $rotate = self::ROTATE_DEFAULT, $distance = self::DISTANCE_DEFAULT, $position = self::POSITION_TOP, $show = true)
    {
        $this->formatter = $formatter;
        $this->rotate = $rotate;
        $this->distance = $distance;
        $this->position = $position;
        $this->show = $show;
    }
}