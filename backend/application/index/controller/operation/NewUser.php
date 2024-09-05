<?php

namespace app\index\controller\operation;

use app\common\controller\BaseController;
use app\common\model\backend\operation\NewUser as model;
use app\index\tool\echarts\NewUser as echarts;
use think\facade\Response;
use app\common\tool\excel\export\ExportExcel;

class NewUser extends BaseController
{
    /**
     * 导出excel文件名
     * @var string
     */
    protected $excelFileName = '新增用户';

    /**
     * 显示新增用户
     * @return mixed|\think\response
     */
    public function index()
    {
        if (!$this->request->isAjax()) {
            return $this->fetch('operation/new_user/index', [
                'platform' => model::getPlatform(),
                'channel' => model::getChanel(),
                'tableClos' => model::getLayUiTableClos(),
            ]);
        }

        $data = model::getPaginationAndEchartsData($this->request->param());
        $paginator = $data['paginator'];

        return Response::create([
            'table' => [
                'data' => $paginator->items(),
                'count' => $paginator->total(),
                'code' => 0,
            ],
            'option' => (new echarts())->initOption($data['echarts']),
        ], 'json');
    }

    /**
     * 导出
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function export()
    {
        (new ExportExcel(new model(), $this->request->param(), $this->excelFileName))->exportExcel();
    }
}
