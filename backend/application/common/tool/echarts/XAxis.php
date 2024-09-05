<?php

namespace app\common\tool\echarts;

use app\common\tool\echarts\x_axis\AxisLabel;
use app\common\tool\echarts\x_axis\SplitArea;

/**
 * 直角坐标系grid中的x轴
 * Class XAxis
 * @package app\index\tool\echarts\option
 */
class XAxis
{
    /**
     * 数值轴
     */
    const TYPE_VALUE = 'value';

    /**
     * 类目轴
     */
    const TYPE_CATEGORY = 'category';

    /**
     * 时间轴
     */
    const TYPE_TIME = 'time';

    /**
     * 对数轴
     */
    const TYPE_LOG = 'log';

    /**
     * x轴坐标类型
     * @var string
     */
    public $type;

    /**
     * x轴坐标名称
     * @var
     */
    public $name;

    /**
     * 坐标轴刻度标签的相关设置
     * @var
     */
    public $axisLabel;

    /**
     * 坐标轴在 grid 区域中的分隔区域
     * @var
     */
    public $splitArea;

    /**
     * 类目数据，在类目轴中有效
     * 注意事项：1.如果没有设置type，但是设置了axis.data则认为type是category（类目轴）
     *         2.如果设置了type是category，但没有设置axis.data，则axis.data的内容会自动从series.data中获取
     * @var
     */
    public $data;

    /**
     * 初始化
     * XAxis constructor.
     * @param $type
     * @param $name
     * @param null $data
     * @param string $interval
     * @param int $rotate
     * @param false $splitAreaShow
     */
    public function __construct($type, $name, $data = null, $interval = AxisLabel::INTERVAL_DEFAULT, $rotate = AxisLabel::ROTATE_DEFAULT, $splitAreaShow = false)
    {
        $this->type = $type;
        $this->name = $name;
        if (!empty($data)) {
            $this->data = $data;
        }
        $this->axisLabel = new AxisLabel($interval, $rotate);
        $this->splitArea = new SplitArea($splitAreaShow);
    }
}