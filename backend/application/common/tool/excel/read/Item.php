<?php

namespace app\common\tool\excel\read;

use app\common\tool\excel\read\filter\ConfHeaderReadFilter;
use app\common\tool\excel\read\filter\ConfReadFilter;

/**
 * 获取附件
 * Class Entry
 * @package app\index\excel
 */
class Item extends ReadExcel
{
    /**
     * excel表名
     * @var string
     */
    protected $fileName = 'Items.xlsx';

    /**
     * id表头标识
     * @var string
     */
    protected $idHeader = 'ID';

    /**
     * id所在列
     * @var
     */
    protected $idColumn;

    /**
     * 名称表头标识
     * @var string
     */
    protected $nameHeader = 'Comment';

    /**
     * 名称所在列
     * @var
     */
    protected $nameColumn;

    /**
     * 剔除项id
     * @var int[]
     */
    protected $cullItemId = [50];

    /**
     * 配置
     * @var array
     */
    protected $config = [];

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
        $this->setConfig();
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
                $this->setIdReadColumn($coordinate);
            }

            if ($value == $this->nameHeader) {
                $this->setNameReadColumn($coordinate);
            }

            if ($this->isSetAllColumn()) {
                break;
            }
        }
    }

    /**
     * 设置id所在列
     * @param $coordinate
     */
    protected function setIdReadColumn($coordinate)
    {
        $this->idColumn = $this->getColumn($coordinate);
    }

    /**
     * 设置名称所在列
     * @param $coordinate
     */
    protected function setNameReadColumn($coordinate)
    {
        $this->nameColumn = $this->getColumn($coordinate);
    }

    /**
     * 是否设置所有列
     * @return bool
     */
    protected function isSetAllColumn()
    {
        return !empty($this->idColumn) && !empty($this->nameColumn);
    }

    /**
     * 设置配置
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    protected function setConfig()
    {
        $readOnlyColumn = [$this->idColumn, $this->nameColumn];
        $this->reader->setReadFilter(new ConfReadFilter($readOnlyColumn, $this->confStartRow));
        $spreadsheet = $this->reader->load($this->fileCompletePath);
        $worksheet = $spreadsheet->getActiveSheet();
        $coordinates = $worksheet->getCoordinates();

        foreach ($coordinates as $coordinate) {
            sscanf($coordinate, '%[A-Z]%d', $c, $r);
            if ($c == $this->idColumn) {
                $id = $worksheet->getCell($coordinate)->getValue();
                $name = $worksheet->getCell($this->nameColumn . $r)->getValue();
                $this->config[$id] = $name;
            }
        }

        // 剔除指定id项
        $this->cullItem();
    }

    /**
     * 剔除项
     */
    protected function cullItem()
    {
        foreach ($this->cullItemId as $id) {
            unset($this->config[$id]);
        }
    }

    /**
     * 获取配置
     * @return array
     */
    public function getConfig()
    {
        return $this->config;
    }
}