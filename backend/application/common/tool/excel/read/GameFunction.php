<?php

namespace app\common\tool\excel\read;

use app\common\tool\excel\read\filter\ConfHeaderReadFilter;
use app\common\tool\excel\read\filter\ConfReadFilter;

/**
 * 获取游戏功能配置
 * Class Entry
 * @package app\index\excel
 */
class GameFunction extends ReadExcel
{
    /**
     * excel表名
     * @var string
     */
    protected $fileName = 'Function.xlsx';

    /**
     * 功能id表头标识
     * @var string
     */
    protected $functionIdHeader = 'ID';

    /**
     * 功能id所在列
     * @var
     */
    protected $functionIdColumn;

    /**
     * 功能名表头标识
     * @var string
     */
    protected $functionNameHeader = 'Comment';

    /**
     * 功能名所在列
     * @var
     */
    protected $functionNameColumn;

    /**
     * 功能配置
     * @var array
     */
    protected $functionConfig = [];

    /**
     * 初始化excel文件路径
     */
    protected function initFilePath()
    {
        $this->fileCompletePath = implode(DIRECTORY_SEPARATOR, [$this->rootPath, $this->fileName]);
    }

    /**
     * 初始化
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    protected function init()
    {
        $this->initReadColumn();
        $this->setFunctionConfig();
    }

    /**
     * 初始化需要读取的列
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    protected function initReadColumn()
    {
        $this->reader->setReadFilter(new ConfHeaderReadFilter());
        $spreadsheet = $this->reader->load($this->fileCompletePath);
        $worksheet = $spreadsheet->getActiveSheet();
        $coordinates = $worksheet->getCoordinates();

        foreach ($coordinates as $coordinate) {
            $value = $worksheet->getCell($coordinate)->getValue();
            if ($value == $this->functionIdHeader) {
                $this->setFunctionIdColumn($coordinate);
            }

            if ($value == $this->functionNameHeader) {
                $this->setFunctionNameColumn($coordinate);
            }

            if ($this->isSetAllColumn()) {
                break;
            }
        }
    }

    /**
     * 设置功能id所在列
     * @param $coordinate
     */
    protected function setFunctionIdColumn($coordinate)
    {
        $this->functionIdColumn = $this->getColumn($coordinate);
    }


    /**
     * 设置功能名称所在列
     * @param $coordinate
     */
    protected function setFunctionNameColumn($coordinate)
    {
        $this->functionNameColumn = $this->getColumn($coordinate);
    }

    /**
     * 是否设置所有列
     * @return bool
     */
    protected function isSetAllColumn()
    {
        return !empty($this->functionIdColumn) && !empty($this->functionNameColumn);
    }
    
    /**
     * 设置功能配置
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    protected function setFunctionConfig()
    {
        $readOnlyColumn = [$this->functionIdColumn, $this->functionNameColumn];
        $this->reader->setReadFilter(new ConfReadFilter($readOnlyColumn, $this->confStartRow));
        $spreadsheet = $this->reader->load($this->fileCompletePath);
        $worksheet = $spreadsheet->getActiveSheet();
        $coordinates = $worksheet->getCoordinates();

        foreach ($coordinates as $coordinate) {
            sscanf($coordinate, '%[A-Z]%d', $c, $r);
            if ($c == $this->functionIdColumn) {
                $id = $worksheet->getCell($coordinate)->getValue();
                $name = $worksheet->getCell($this->functionNameColumn . $r)->getValue();
                $this->functionConfig[$id] = $name;
            }
        }
    }

    /**
     * 获取功能配置
     * @return array
     */
    public function getFunctionConfig()
    {
        return $this->functionConfig;
    }
}