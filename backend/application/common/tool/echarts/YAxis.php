<?php

namespace app\common\tool\echarts;

/**
 * 直角坐标系grid中的y轴
 * Class YAxis
 * @package app\index\tool\echarts\option
 */
class YAxis
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
     * x轴坐标类型，默认为数值轴
     * @var string
     */
    public $type;

    /**
     * y轴坐标名称
     * @var
     */
    public $name;

    /**
     * 初始化
     * YAxis constructor.
     * @param $type
     * @param $name
     */
    public function __construct($type, $name)
    {
        $this->type = $type;
        $this->name = $name;
    }
}