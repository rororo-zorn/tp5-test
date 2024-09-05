<?php

namespace app\common\tool\excel\read\filter;

use PhpOffice\PhpSpreadsheet\Reader\IReadFilter;

class ConfReadFilter implements IReadFilter
{
    /**
     * 只读列
     * @var
     */
    private $column;

    /**
     * 只读行（起始位置）
     * @var
     */
    private $row;

    /**
     * 初始化
     * ConfReadFilter constructor.
     * @param $column
     * @param $row
     */
    public function __construct($column, $row)
    {
        $this->column = $column;
        $this->row = $row;
    }

    /**
     * 读取规则
     * @param string $column
     * @param int $row
     * @param string $worksheetName
     * @return bool
     */
    public function readCell($column, $row, $worksheetName = '')
    {
        if ((is_array($this->column) ? in_array($column, $this->column) : $column == $this->column) && $row >= $this->row) {
            return true;
        }

        return false;
    }
}