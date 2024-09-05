<?php

namespace app\common\tool\excel\read;

use app\common\tool\excel\read\filter\ConfHeaderReadFilter;
use app\common\tool\excel\read\filter\ConfReadFilter;

/**
 * 获取广告轮播图配置
 * Class Entry
 * @package app\index\excel
 */
class Rotation extends ReadExcel
{
    /**
     * excel表名
     * @var string
     */
    protected $fileName = 'Rotation.xlsx';

    /**
     * id表头标识
     * @var string
     */
    protected $idHeader = 'ID';

    /**
     * 名称表头标识
     * @var string
     */
    protected $nameHeader = 'Comment';

    /**
     * 开始时间表头标识
     * @var string
     */
    protected $starTimeHeader = 'Start_Time';

    /**
     * 截至时间表头标识
     * @var string
     */
    protected $endTimeHeader = 'End_Time';

    /**
     * id所在列
     * @var
     */
    protected $idColumn;


    /**
     * 名称所在列
     * @var
     */
    protected $nameColumn;


    /**
     * 开始时间所在列
     * @var
     */
    protected $startTimeColumn;

    /**
     * 截至时间所在列
     * @var
     */
    protected $endTimeColumn;

    /**
     * 广告轮播图配置
     * @var array
     */
    protected $adConfig = [];

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
        $this->setStoreConfig();
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
            if ($value == $this->idHeader) {
                $this->idColumn = $this->getColumn($coordinate);
            }

            if ($value == $this->nameHeader) {
                $this->nameColumn = $this->getColumn($coordinate);
            }

            if ($value == $this->starTimeHeader) {
                $this->startTimeColumn = $this->getColumn($coordinate);
            }

            if ($value == $this->endTimeHeader) {
                $this->endTimeColumn = $this->getColumn($coordinate);
            }
        }
    }

    /**
     * 设置商城配置
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    protected function setStoreConfig()
    {
        $readOnlyColumn = [$this->idColumn, $this->nameColumn, $this->startTimeColumn, $this->endTimeColumn];
        $this->reader->setReadFilter(new ConfReadFilter($readOnlyColumn, $this->confStartRow));
        $spreadsheet = $this->reader->load($this->fileCompletePath);
        $worksheet = $spreadsheet->getActiveSheet();
        $coordinates = $worksheet->getCoordinates();

        foreach ($coordinates as $coordinate) {
            sscanf($coordinate, '%[A-Z]%d', $c, $r);
            if ($c == $this->idColumn) {
                $id = $worksheet->getCell($coordinate)->getValue();
                $name = $worksheet->getCell($this->nameColumn . $r)->getValue();
                $startTime = $worksheet->getCell($this->startTimeColumn . $r)->getValue();
                $endTime = $worksheet->getCell($this->endTimeColumn . $r)->getValue();
                $this->adConfig[$id] = [
                    'id' => $id,
                    'name' => $name,
                    'startTime' => $startTime,
                    'endTime' => $endTime,
                    'enable' => true,
                ];
            }
        }
    }

    /**
     * 获取广告轮播图配置
     * @return array
     */
    public function getAdConfig()
    {
        return $this->adConfig;
    }
}