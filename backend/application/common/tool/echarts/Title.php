<?php

namespace app\common\tool\echarts;

/**
 * 标题
 * Class Title
 * @package app\index\tool\echarts
 */
class Title
{
    /**
     * 主标题文本
     * @var
     */
    public $text;

    /**
     * 副标题文本，支持使用\n换行
     * @var
     */
    public $subtext;

    /**
     * 初始化
     * Title constructor.
     * @param $text
     * @param null $subtext
     */
    public function __construct($text, $subtext = null)
    {
        $this->text = $text;
        $this->subtext = $subtext;
    }
}