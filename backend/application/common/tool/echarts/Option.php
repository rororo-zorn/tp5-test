<?php

namespace app\common\tool\echarts;

use app\common\tool\echarts\toolbox\Feature;
use app\common\tool\echarts\toolbox\feature\SaveAsImage;
use app\common\tool\echarts\toolbox\feature\DataView;

class Option
{
    /**
     * 标题组件
     * @var
     */
    public $title;

    /**
     * 图例组件
     * @var
     */
    public $legend;

    /**
     * 直角坐标系内绘图网格
     * @var
     */
    public $grid;

    /**
     * 直角坐标系grid中的x轴
     * @var
     */
    public $xAxis;

    /**
     * 直角坐标系grid中的y轴
     * @var
     */
    public $yAxis;

    /**
     * 区域缩放组件
     * @var
     */
    public $dataZoom;

    /**
     * 视觉映射组件
     * @var
     */
    public $visualMap;

    /**
     * 提示框组件
     * @var
     */
    public $tooltip;

    /**
     * 工具栏
     * @var
     */
    public $toolbox;

    /**
     * 系列
     * @var
     */
    public $series;

    /**
     * 初始化
     * Option constructor.
     */
    public function __construct()
    {
        // 初始化区域缩放和工具栏
        $this->initDataZoom();
        $this->initToolbox();
    }

    /**
     * 初始化区域缩放
     */
    protected function initDataZoom()
    {
        $this->dataZoom = [
            new DataZoom(DataZoom::TYPE_INSIDE),    // 内置型数据区域缩放
            new DataZoom(DataZoom::TYPE_SLIDER),    // 滑动条型数据区域缩放
        ];
    }

    /**
     * 初始化工具栏
     */
    protected function initToolbox()
    {
        $this->toolbox = new Toolbox();
        $this->toolbox->feature = new Feature();
        $this->toolbox->feature->saveAsImage = new SaveAsImage();   // 保存图片
        $this->toolbox->feature->dataView = new DataView(); // 数据视图
    }
}