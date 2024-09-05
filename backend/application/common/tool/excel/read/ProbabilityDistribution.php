<?php

namespace app\common\tool\excel\read;

use app\common\tool\excel\read\filter\ConfHeaderReadFilter;
use app\common\tool\excel\read\filter\ConfReadFilter;

/**
 * 获取倍率分布
 * Class ProbabilityDistribution
 * @package app\common\tool\excel\read
 */
class ProbabilityDistribution extends ReadExcel
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
    protected $fileName = 'P200_Slots_ProbabilityDistribution.xlsx';

    /**
     * 金币档次（默认为第二档次）
     * @var int
     */
    protected $goldGrade = 2;

    /**
     * 金币档次表头标识
     * @var string
     */
    protected $goldGearHeader = 'GoldGear';

    /**
     * 金币档次定义列，可作为选择倍率的唯一标识
     * @var string
     */
    protected $goldGardeColumn;

    /**
     * 中奖倍率左区间表头标识
     * @var string
     */
    protected $wMLeftIntervalHeader = 'ProbabilityDistribution1';

    /**
     * 中奖倍率左区间定义列
     * @var string
     */
    protected $wMLeftIntervalColumn;

    /**
     * 中奖倍率右区间表头标识
     * @var string
     */
    protected $vMRightIntervalHeader = 'ProbabilityDistribution2';

    /**
     * 中奖倍率右区间定义列
     * @var string
     */
    protected $wMRightIntervalColumn;

    /**
     * 中奖倍率
     * @var array
     */
    protected $winningMultiple = [];

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
        $this->setWinningMultiple();
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
            if ($value == $this->goldGearHeader) {
                $this->setGoldGearColumn($coordinate);
            }

            if ($value == $this->wMLeftIntervalHeader) {
                $this->setWinMultipleLeftIntervalColumn($coordinate);
            }

            if ($value == $this->vMRightIntervalHeader) {
                $this->setWinMultipleRightIntervalColumn($coordinate);
            }

            if ($this->isSetAllColumn()) {
                break;
            }
        }
    }

    /**
     * 设置金币档次所在列
     * @param $coordinate
     */
    protected function setGoldGearColumn($coordinate)
    {
        $this->goldGardeColumn = $this->getColumn($coordinate);
    }

    /**
     * 设置中奖倍率左区间所在列
     * @param $coordinate
     */
    protected function setWinMultipleLeftIntervalColumn($coordinate)
    {
        $this->wMLeftIntervalColumn = $this->getColumn($coordinate);
    }

    /**
     * 设置中奖倍率右区间所在列
     * @param $coordinate
     */
    protected function setWinMultipleRightIntervalColumn($coordinate)
    {
        $this->wMRightIntervalColumn = $this->getColumn($coordinate);
    }

    /**
     * 是否设置所有列
     * @return bool
     */
    protected function isSetAllColumn()
    {
        return !empty($this->goldGardeColumn) && !empty($this->wMLeftIntervalColumn) && !empty($this->wMRightIntervalColumn);
    }

    /**
     * 设置中奖倍率
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    protected function setWinningMultiple()
    {
        $readOnlyColumn = [$this->goldGardeColumn, $this->wMLeftIntervalColumn, $this->wMRightIntervalColumn];
        $this->reader->setReadFilter(new ConfReadFilter($readOnlyColumn, $this->confStartRow));
        $spreadsheet = $this->reader->load($this->fileCompletePath);
        $worksheet = $spreadsheet->getActiveSheet();
        $coordinates = $worksheet->getCoordinates();

        foreach ($coordinates as $coordinate) {
            sscanf($coordinate, '%[A-Z]%d', $c, $r);
            if ($c == $this->goldGardeColumn && $worksheet->getCell($coordinate)->getValue() == $this->goldGrade) {
                $left = intval($worksheet->getCell($this->wMLeftIntervalColumn . $r)->getValue());
                $right = intval($worksheet->getCell($this->wMRightIntervalColumn . $r)->getValue());
                $this->winningMultiple[] = [$left, $right];
            }
        }
    }

    /**
     * 获取中奖倍率
     * @return array
     */
    public function getWinningMultiple()
    {
        return array_values($this->winningMultiple);
    }
}