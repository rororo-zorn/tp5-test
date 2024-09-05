<?php

namespace app\common\tool\excel\read;

use app\common\tool\excel\read\filter\ConfHeaderReadFilter;
use app\common\tool\excel\read\filter\ConfReadFilter;

/**
 * 获取机台配置
 * Class Entry
 * @package app\index\excel
 */
class StarLevel extends ReadExcel
{
    /**
     * excel表名
     * @var string
     */
    protected $fileName = 'StarLevel.xlsx';

    /**
     * 机台id表头标识
     * @var string
     */
    protected $gameIdHeader = 'ID';

    /**
     * 机台id所在列
     * @var
     */
    protected $gameIdColumn;

    /**
     * 机台名表头标识
     * @var string
     */
    protected $gameNameHeader = 'Comment';

    /**
     * 机台名所在列
     * @var
     */
    protected $gameNameColumn;

    /**
     * 开关表头标识
     * @var string
     */
    protected $switchHeader = 'GameSwitch';

    /**
     * 开关所在列
     * @var
     */
    protected $switchColumn;

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
            if ($value == $this->gameIdHeader) {
                $this->setGameIdColumn($coordinate);
            }

            if ($value == $this->gameNameHeader) {
                $this->setGameNameColumn($coordinate);
            }

            if ($value == $this->switchHeader) {
                $this->setSwitchColumn($coordinate);
            }

            if ($this->isSetAllColumn()) {
                break;
            }
        }
    }

    /**
     * 设置机台id所在列
     * @param $coordinate
     */
    protected function setGameIdColumn($coordinate)
    {
        $this->gameIdColumn = $this->getColumn($coordinate);
    }

    /**
     * 设置机台名所在列
     * @param $coordinate
     */
    protected function setGameNameColumn($coordinate)
    {
        $this->gameNameColumn = $this->getColumn($coordinate);
    }

    /**
     * 设置开关所在列
     * @param $coordinate
     */
    protected function setSwitchColumn($coordinate)
    {
        $this->switchColumn = $this->getColumn($coordinate);
    }

    /**
     * 是否设置所有列
     * @return bool
     */
    protected function isSetAllColumn()
    {
        return !empty($this->gameIdColumn) && !empty($this->gameNameColumn) && !empty($this->switchColumn);
    }

    /**
     * 设置配置
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    protected function setConfig()
    {
        $readOnlyColumn = [$this->gameIdColumn, $this->gameNameColumn, $this->switchColumn];
        $this->reader->setReadFilter(new ConfReadFilter($readOnlyColumn, $this->confStartRow));
        $spreadsheet = $this->reader->load($this->fileCompletePath);
        $worksheet = $spreadsheet->getActiveSheet();
        $coordinates = $worksheet->getCoordinates();

        foreach ($coordinates as $coordinate) {
            sscanf($coordinate, '%[A-Z]%d', $c, $r);
            if ($c == $this->gameIdColumn) {
                $id = $worksheet->getCell($coordinate)->getValue();
                $name = $worksheet->getCell($this->gameNameColumn . $r)->getValue();
                $state = $worksheet->getCell($this->switchColumn . $r)->getValue();
                $this->config[] = [
                    'id' => $id,
                    'name' => $name,
                    'state' => $state,
                ];
            }
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