<?php

namespace app\index\tool\echarts;

use app\common\tool\echarts\Option;
use app\common\tool\echarts\Title;
use app\common\tool\echarts\Legend;
use app\common\tool\echarts\Grid;
use app\common\tool\echarts\toolbox\feature\MagicType;
use app\common\tool\echarts\XAxis;
use app\common\tool\echarts\YAxis;
use app\common\tool\echarts\Tooltip;
use app\common\tool\echarts\Series;

class NewUser extends Option
{
    /**
     * 主标题
     * @var string
     */
    protected $mainTitle = '新增用户';

    /**
     * x轴坐标名称
     * @var string
     */
    protected $xAxisName = '日期';

    /**
     * y轴坐标名称
     * @var string
     */
    protected $yAxisName = '人数';

    /**
     * 直角坐标系内绘图网格
     * @var
     */
    public $grid;

    /**
     * 系列
     * @var
     */
    public $series;

    /**
     * 初始化折线图配置
     * @param $seriesData
     * @return $this
     */
    public function initOption($seriesData)
    {
        $this->title = new Title($this->mainTitle);
        $this->legend = new Legend(Legend::TYPE_SCROLL);
        $this->grid = new Grid();
        $this->xAxis = new XAxis(XAxis::TYPE_CATEGORY, $this->xAxisName);
        $this->yAxis = new YAxis(YAxis::TYPE_VALUE, $this->yAxisName);
        $this->tooltip = new Tooltip();
        $this->toolbox->feature->magicType = new MagicType();
        $this->series = $this->initSeries($seriesData);
        return $this;
    }

    /**
     * 初始化系列
     * @param $seriesData
     * @return array
     */
    protected function initSeries($seriesData)
    {
        $series = [];
        foreach ($seriesData as $seriesName => $data) {
            $series[] = new Series(Series::TYPE_LINE, $seriesName, array_reverse($data));
        }

        return $series;
    }
}