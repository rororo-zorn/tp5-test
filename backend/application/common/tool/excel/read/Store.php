<?php

namespace app\common\tool\excel\read;

use app\common\tool\excel\read\filter\ConfHeaderReadFilter;
use app\common\tool\excel\read\filter\ConfReadFilter;

/**
 * 获取商城配置
 * Class Entry
 * @package app\index\excel
 */
class Store extends ReadExcel
{
    /**
     * excel表名
     * @var string
     */
    protected $fileName = 'Store.xlsx';

    /**
     * 商品ID所在列
     * @var
     */
    protected $commodityIDColumn = 'A';

    /**
     * 商品名称所在列
     * @var
     */
    protected $commodityNameColumn = 'B';

    /**
     * 商品单价表头标识
     * @var string
     */
    protected $priceHeader = 'Price';

    /**
     * 商品单价所在列
     * @var
     */
    protected $priceColumn;

    /**
     * 商城配置
     * @var array
     */
    protected $storeConfig = [];

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
            if ($value == $this->priceHeader) {
                $this->setPriceColumn($coordinate);
                break;
            }
        }
    }

    /**
     * 设置商品价格所在列
     * @param $coordinate
     */
    protected function setPriceColumn($coordinate)
    {
        $this->priceColumn = $this->getColumn($coordinate);
    }

    /**
     * 设置商城配置
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    protected function setStoreConfig()
    {
        $readOnlyColumn = [$this->commodityIDColumn, $this->commodityNameColumn, $this->priceColumn];
        $this->reader->setReadFilter(new ConfReadFilter($readOnlyColumn, $this->confStartRow));
        $spreadsheet = $this->reader->load($this->fileCompletePath);
        $worksheet = $spreadsheet->getActiveSheet();
        $coordinates = $worksheet->getCoordinates();

        foreach ($coordinates as $coordinate) {
            sscanf($coordinate, '%[A-Z]%d', $c, $r);
            if ($c == $this->commodityIDColumn) {
                $id = $worksheet->getCell($coordinate)->getValue();
                $name = $worksheet->getCell($this->commodityNameColumn . $r)->getValue();
                $price = $worksheet->getCell($this->priceColumn . $r)->getValue();
                $this->storeConfig[$id] = [
                    'name' => $name,
                    'price' => $price,
                ];
            }
        }
    }

    /**
     * 获取商城配置
     * @return array
     */
    public function getStoreConfig()
    {
        return $this->storeConfig;
    }
}