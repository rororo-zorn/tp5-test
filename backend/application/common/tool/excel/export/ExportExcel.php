<?php

namespace app\common\tool\excel\export;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ExportExcel
{
    /**
     * 数据填充起始坐标
     * @var string
     */
    protected $startCell = 'A1';

    /**
     * 当前执行次数（分页查询）
     * @var int
     */
    protected $currExecuteTimes = 0;

    /**
     * 总执行次数（分页查询）
     * @var
     */
    protected $totalExecuteTimes;

    /**
     * excel文件后缀
     * @var string
     */
    protected $suffix = 'Xlsx';

    /**
     * 当前模型对象
     * @var
     */
    protected $model;

    /**
     * 当前请求参数
     * @var array
     */
    protected $requestParam = [
        'limit' => 50000,
    ];

    /**
     * excel文件名（含后缀）
     * @var
     */
    protected $fileName;

    /**
     * layui table cols
     * ['title' => 'field']
     * @var
     */
    protected $clos;

    /**
     * excel表数据（含表头）
     * @var array
     */
    protected $data = [];

    public function __construct($model, $requestParam, $fileName)
    {
        $this->model = $model;
        $this->requestParam = array_merge($requestParam, $this->requestParam);  // 合并limit
        $this->fileName = sprintf('%s.%s', $fileName, $this->suffix);

        // 初始化请求参数page
        app('request')->page = 1;

        // 初始化
        $this->init();
    }

    /**
     * 初始化
     */
    protected function init()
    {
        $this->parseLayuiTableCols();

        $this->setExcelTitle();

        $this->handleExcelData();
    }

    /**
     * 解析layui table cols
     * 排除table操作、多选框等
     */
    protected function parseLayuiTableCols()
    {
        $clos = array_column(call_user_func([$this->model, 'getLayUiTableClos']), 'field', 'title');
        foreach ($clos as $key => $clo) {
            if ($clo == '') {
                unset($clos[$key]);
            }
        }

        $this->clos = $clos;
    }

    /**
     * 设置表头
     */
    protected function setExcelTitle()
    {
        $this->data[] = array_keys($this->clos);
    }

    /**
     * 设置page
     */
    protected function setPage()
    {
        app('request')->page++;
    }

    /**
     * 构建表数据（二维数组）
     */
    protected function handleExcelData()
    {
        $field = array_values($this->clos);

        do {
            $paginator = call_user_func([$this->model, 'getPaginationData'], $this->requestParam);
            if ($this->totalExecuteTimes == null) {
                $this->totalExecuteTimes = $paginator->lastPage();  // 总执行次数=总页数
            }

            $collection = $paginator->items();
            foreach ($collection as $model) {
                $data = [];
                foreach ($field as $f) {
                    $data[] = $model->$f;
                }
                $this->data[] = $data;
            }

            $this->currExecuteTimes++;
            $this->setPage();
        } while ($this->currExecuteTimes < $this->totalExecuteTimes);
    }

    /**
     * 导出
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function exportExcel()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->fromArray($this->data, null, $this->startCell, true);

        // header参数
        header("Content-Type:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
        header("Content-Disposition:attachment;filename={$this->fileName}");
        header("Cache-Control:max-age=0");

        $writer = IOFactory::createWriter($spreadsheet, $this->suffix);
        $writer->save("php://output");
    }
}