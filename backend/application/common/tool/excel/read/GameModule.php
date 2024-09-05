<?php

namespace app\common\tool\excel\read;

use app\common\tool\excel\read\filter\ConfReadFilter;

/**
 * 获取游戏模块配置
 * Class Entry
 * @package app\index\excel
 */
class GameModule extends ReadExcel
{
    /**
     * excel表名
     * @var string
     */
    protected $fileName = 'GameModules.xlsx';

    /**
     * 模块名称所在列
     * @var
     */
    protected $moduleNameColumn = 'B';

    /**
     * 类型所在列
     * @var string
     */
    protected $typeColumn = 'C';

    /**
     * 子类型所在列
     * @var string
     */
    protected $childColumn = 'D';

    /**
     * 游戏模块
     * @var array
     */
    protected $gameModule = [];

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
        $this->setGameModule();
    }

    /**
     * 设置游戏模块
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    protected function setGameModule()
    {
        $readOnlyColumn = [$this->moduleNameColumn, $this->typeColumn, $this->childColumn];
        $this->reader->setReadFilter(new ConfReadFilter($readOnlyColumn, $this->confStartRow));
        $spreadsheet = $this->reader->load($this->fileCompletePath);
        $worksheet = $spreadsheet->getActiveSheet();
        $coordinates = $worksheet->getCoordinates();

        foreach ($coordinates as $coordinate) {
            sscanf($coordinate, '%[A-Z]%d', $c, $r);
            if ($c == $this->typeColumn) {
                $type = $worksheet->getCell($coordinate)->getValue();
                $child = $worksheet->getCell($this->childColumn . $r)->getValue();
                if ($type == 1 && $child == 0) {
                    continue;
                }
                $name = $worksheet->getCell($this->moduleNameColumn . $r)->getValue();


                $this->gameModule[] = [
                    'id' => $type . '-' . $child,
                    'type' => $type,
                    'child' => $child,
                    'name' => $name,
                    'state' => 1,
                ];
            }
        }
    }

    /**
     * 获取游戏模块
     * @return array
     */
    public function getGameModule()
    {
        return $this->gameModule;
    }
}