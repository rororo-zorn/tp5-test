<?php

namespace app\common\tool\excel\read;

use app\common\tool\excel\read\filter\ConfHeaderReadFilter;
use app\common\tool\excel\read\filter\ConfReadFilter;

/**
 * 获取大奖倍率区间
 * Class BigWinMultiple
 * @package app\common\tool\excel\read
 */
class BigWinMultiple extends ReadExcel
{
    /**
     * 文件夹
     * @var string
     */
    protected $folder = 'Slots_P200';

    /**
     * excel表名（默认以p200为准）
     * @var string
     */
    protected $fileName = 'P200_Slots_Entry.xlsx';

    /**
     * 连线数表头标识
     * @var string
     */
    protected $lineNumHeader = 'ConnectionNumber';

    /**
     * 连线数所在列
     * @var
     */
    protected $lineNumColumn;

    /**
     * WinType表头标识
     * @var string
     */
    protected $winTypeHeader = 'WinType';

    /**
     * WinType所在列
     * @var
     */
    protected $winTypeColumn;

    /**
     * BigWinLevel表头标识
     * @var string
     */
    protected $bigWinLevelHeader = 'BigWinLevel';

    /**
     * BigWinLevel所在列
     * @var
     */
    protected $bigWinLevelColumn;

    /**
     * 连线数
     * @var
     */
    protected $lineNum;

    /**
     * winType
     * @var
     */
    protected $winType;

    /**
     * bigWinLevel
     * @var
     */
    protected $bigWinLevel;

    /**
     * 分割符
     * @var string
     */
    protected $explodeChar = '*';

    /**
     * 大奖类型
     * @var string[]
     */
    protected $bigWinType = ['bigWin', 'superWin', 'megaWin', 'epicWin', 'legendaryWin'];

    /**
     * 大奖配置
     * @var array
     */
    protected $bigWinMultipleConf = [];

    /**
     * 初始化excel文件路径
     */
    protected function initFilePath()
    {
        $this->fileCompletePath = implode(DIRECTORY_SEPARATOR, [$this->rootPath, $this->folder, $this->fileName]);
    }

    /**
     * 初始化
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    protected function init()
    {
        $this->initReadColumn();
        $this->setWinTypeAndBigWinLevel();
        $this->setBigWinMultipleConf();
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
            if ($value == $this->lineNumHeader) {
                $this->setLineNumColumn($coordinate);
            }

            if ($value == $this->winTypeHeader) {
                $this->setWinTypeColumn($coordinate);
            }

            if ($value == $this->bigWinLevelHeader) {
                $this->setBigWinLevelColumn($coordinate);
            }

            if ($this->isSetAllColumn()) {
                break;
            }
        }
    }

    /**
     * 设置连线数所在列
     * @param $coordinate
     */
    protected function setLineNumColumn($coordinate)
    {
        $this->lineNumColumn = $this->getColumn($coordinate);
    }

    /**
     * 设置winType所在列
     * @param $coordinate
     */
    protected function setWinTypeColumn($coordinate)
    {
        $this->winTypeColumn = $this->getColumn($coordinate);
    }

    /**
     * 设置bigWinLevel所在列
     * @param $coordinate
     */
    protected function setBigWinLevelColumn($coordinate)
    {
        $this->bigWinLevelColumn = $this->getColumn($coordinate);
    }

    /**
     * 是否设置所有列
     * @return bool
     */
    protected function isSetAllColumn()
    {
        return !empty($this->lineNum) && !empty($this->winTypeColumn) && !empty($this->bigWinLevelColumn);
    }

    /**
     * 设置winType和bigWinLevel
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    protected function setWinTypeAndBigWinLevel()
    {
        $readOnlyColumn = [$this->lineNumColumn, $this->winTypeColumn, $this->bigWinLevelColumn];
        $this->reader->setReadFilter(new ConfReadFilter($readOnlyColumn, $this->confStartRow));
        $spreadsheet = $this->reader->load($this->fileCompletePath);
        $worksheet = $spreadsheet->getActiveSheet();
        $coordinates = $worksheet->getCoordinates();

        foreach ($coordinates as $coordinate) {
            sscanf($coordinate, '%[A-Z]%d', $c, $r);

            if ($c == $this->lineNumColumn) {
                $this->lineNum = $worksheet->getCell($coordinate)->getValue();
                $this->winType = $worksheet->getCell($this->winTypeColumn . $r)->getValue();
                $this->bigWinLevel = $worksheet->getCell($this->bigWinLevelColumn . $r)->getValue();
                break;
            }
        }
    }

    /**
     * 设置大奖倍率
     */
    protected function setBigWinMultipleConf()
    {
        $winType = explode($this->explodeChar, $this->winType);

        $count = count($winType);
        $bigWinMultiple = [];
        for ($i = $this->bigWinLevel - 1; $i < $count - 1; $i++) {
            $bigWinMultiple[] = [
                'min' => $winType[$i] * $this->lineNum,
                'max' => $winType[$i + 1] * $this->lineNum,
            ];
        }
        
        foreach ($bigWinMultiple as $key => $multiple) {
            $this->bigWinMultipleConf[$this->bigWinType[$key]] = $multiple;
        }
    }

    /**
     * 获取大奖倍率
     * @return array
     */
    public function getBigWinMultipleConf()
    {
        return $this->bigWinMultipleConf;
    }
}