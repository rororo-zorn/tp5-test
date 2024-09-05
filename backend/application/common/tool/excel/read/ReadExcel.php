<?php

namespace app\common\tool\excel\read;

use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

abstract class ReadExcel
{
    /**
     * excel文件根路径
     * @var mixed
     */
    protected $rootPath;

    /**
     * excel文件完整路径
     * @var
     */
    protected $fileCompletePath;

    /**
     * 配置起始行
     * @var int
     */
    protected $confStartRow = 5;

    /**
     * reader对象
     * @var Xlsx
     */
    protected $reader;

    /**
     * 初始化配置文件根路径
     * BaseExcel constructor.
     */
    public function __construct()
    {
        $this->rootPath = config('excel.root_path');
        $this->initFilePath();
        $this->initReader();
        $this->init();
    }

    /**
     * 初始化配置文件完整路径
     */
    abstract protected function initFilePath();

    /**
     * 初始化reader
     * 设置为只读数据和只读第一个工作簿
     */
    protected function initReader()
    {
        $reader = new Xlsx();
        $reader->setReadDataOnly(true);
        $workSheetList = $reader->listWorksheetNames($this->fileCompletePath);
        $reader->setLoadSheetsOnly($workSheetList[0]);

        $this->reader = $reader;
    }

    /**
     * 初始化
     */
    protected function init()
    {
    }

    /**
     * 获取列
     * @param $coordinate
     * @return mixed
     */
    protected function getColumn($coordinate)
    {
        sscanf($coordinate, '%[A-Z]', $c);
        return $c;
    }
}