<?php

namespace app\common\tool\echarts;

/**
 * 图例
 * Class Legend
 * @package app\index\tool\echarts
 */
class Legend
{
    /**
     * 普通图列
     */
    const TYPE_PLAIN = 'plain';

    /**
     * 可滚动翻页图列
     */
    const TYPE_SCROLL = 'scroll';

    /**
     * 图列类型，默认为plain
     * @var
     */
    public $type;

    /**
     * 图列组件的宽度
     * @var string
     */
    public $width = '70%';

    /**
     * 格式化图例文本
     * @var
     */
    public $formatter;

    /**
     * 初始化
     * Legend constructor.
     * @param string $type
     * @param null $formatter
     */
    public function __construct($type = self::TYPE_PLAIN, $formatter = null)
    {
        $this->type = $type;
        if (!empty($formatter)) {
            $this->formatter = $formatter;
        }
    }
}