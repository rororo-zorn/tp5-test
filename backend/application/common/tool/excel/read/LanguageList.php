<?php

namespace app\common\tool\excel\read;

use app\common\tool\excel\read\filter\ConfHeaderReadFilter;
use app\common\tool\excel\read\filter\ConfReadFilter;

/**
 * 获取多语言配置
 * Class Entry
 * @package app\index\excel
 */
class LanguageList extends ReadExcel
{
    /**
     * excel表名
     * @var string
     */
    protected $fileName = 'LanguageList.xlsx';

    /**
     * 多语言id表头标识
     * @var string
     */
    protected $languageIdHeader = 'Type';

    /**
     * 多语言id所在列
     * @var
     */
    protected $languageIdColumn;

    /**
     * 多语言名表头标识
     * @var string
     */
    protected $languageNameHeader = 'Comment';

    /**
     * 多语言名所在列
     * @var
     */
    protected $languageNameColumn;
    
    /**
     * 多语言默认表头标识
     * @var string
     */
    protected $languageDefaultHeader = 'DefaultLanguage';

    /**
     * 多语言默认所在列
     * @var
     */
    protected $languageDefaultColumn;
    
    /**
     * 多语言配置
     * @var array
     */
    protected $languageConfig = [];

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
        $this->setLanguageConfig();
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
            if ($value == $this->languageIdHeader) {
                $this->setLanguageIdColumn($coordinate);
            }

            if ($value == $this->languageNameHeader) {
                $this->setLanguageNameColumn($coordinate);
            }

            if ($value == $this->languageDefaultHeader) {
                $this->setLanguageDefaultColumn($coordinate);
            }

            if ($this->isSetAllColumn()) {
                break;
            }
        }
    }

    /**
     * 设置多语言id所在列
     * @param $coordinate
     */
    protected function setLanguageIdColumn($coordinate)
    {
        $this->languageIdColumn = $this->getColumn($coordinate);
    }

    /**
     * 设置多语言名称所在列
     * @param $coordinate
     */
    protected function setLanguageNameColumn($coordinate)
    {
        $this->languageNameColumn = $this->getColumn($coordinate);
    }

    /**
     * 设置多语言默认所在列
     * @param $coordinate
     */
    protected function setLanguageDefaultColumn($coordinate)
    {
        $this->languageDefaultColumn = $this->getColumn($coordinate);
    }

    /**
     * 是否设置所有列
     * @return bool
     */
    protected function isSetAllColumn()
    {
        return !empty($this->languageIdColumn) && !empty($this->languageNameColumn) && !empty($this->languageDefaultColumn);
    }
    
    /**
     * 设置多语言配置
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    protected function setLanguageConfig()
    {
        $readOnlyColumn = [$this->languageIdColumn, $this->languageNameColumn, $this->languageDefaultColumn];
        $this->reader->setReadFilter(new ConfReadFilter($readOnlyColumn, $this->confStartRow));
        $spreadsheet = $this->reader->load($this->fileCompletePath);
        $worksheet = $spreadsheet->getActiveSheet();
        $coordinates = $worksheet->getCoordinates();

        foreach ($coordinates as $coordinate) {
            sscanf($coordinate, '%[A-Z]%d', $c, $r);
            if ($c == $this->languageIdColumn) {
                $id = $worksheet->getCell($coordinate)->getValue();
                $name = $worksheet->getCell($this->languageNameColumn . $r)->getValue();
                $default = $worksheet->getCell($this->languageDefaultColumn . $r)->getValue();
                $this->languageConfig[$id] = [
                    'name' => $name,
                    'default' => $default,
                ];
            }
        }
    }

    /**
     * 获取多语言配置
     * @return array
     */
    public function getLanguageConfig()
    {
        return $this->languageConfig;
    }
}