<?php

namespace app\common\tool\excel\read\filter;

use PhpOffice\PhpSpreadsheet\Reader\IReadFilter;

class ConfHeaderReadFilter implements IReadFilter
{
    /**
     * 表头定义行
     * @var int
     */
    protected $headerDefineRow = 1;

    /**
     * 读取规则
     * @param string $column
     * @param int $row
     * @param string $worksheetName
     * @return bool
     */
    public function readCell($column, $row, $worksheetName = '')
    {
        if ($row == $this->headerDefineRow) {
            return true;
        }
        return false;
    }
}