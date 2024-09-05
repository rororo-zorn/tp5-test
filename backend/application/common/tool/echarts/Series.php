<?php

namespace app\common\tool\echarts;

/**
 * 系列配置
 * Class Series
 * @package app\index\tool\echarts
 */
class Series
{
    /**
     * 折线图
     */
    const TYPE_LINE = 'line';

    /**
     * 柱状图
     */
    const TYPE_BAR = 'bar';

    /**
     * 图表类型
     * @var
     */
    public $type;

    /**
     * 系列名称
     * @var
     */
    public $name;

    /**
     * 使用的y轴的index
     * @var int
     */
    public $yAxisIndex;

    /**
     * 图形上的文本标签
     * @var
     */
    public $label;

    /**
     * 是否平滑曲线显示
     * line拥有的属性
     * @var bool
     */
    public $smooth = false;

    /**
     * 降采样策略，默认average
     * @var string
     */
    public $sampling = 'average';

    /**
     * 系列中的数据内容数组
     * @var
     */
    public $data;

    /**
     * 图标标注
     * @var
     */
    public $markPoint;

    /**
     * 是否开始动画，默认false
     * @var bool
     */
    public $animation = false;

    /**
     * 初始化
     * Series constructor.
     * @param $type
     * @param $name
     * @param null $data
     * @param null $label
     * @param int $yAxisIndex
     */
    public function __construct($type, $name, $data = null, $label = null, $yAxisIndex = 0)
    {
        $this->type = $type;
        $this->name = $name;
        $this->yAxisIndex = $yAxisIndex;
        if ($type == self::TYPE_LINE) {
            // 显示最大值和最小值
            $this->markPoint = [
                'data' => [
                    ['type' => 'max', 'name' => '最大值'],
                    ['type' => 'min', 'name' => '最小值'],
                ],
            ];
        }
        if (!empty($data)) {
            $this->data = $data;
        }
        if (!empty($label)) {
            $this->label = $label;
        }
    }

    /**
     * 设置平添
     * @return $this
     */
    public function setSmooth()
    {
        $this->smooth = true;
        return $this;
    }
}