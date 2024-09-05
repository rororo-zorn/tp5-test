<?php

namespace app\index\controller\operation;

use app\common\controller\BaseController;
use app\common\tool\excel\export\ExportExcel;

class Retention extends BaseController
{
    /**
     * 导出excel文件名
     * @var string[]
     */
    protected $excelFileName = [
        'exportNewRetention' => '新增留存',
        'exportActiveRetention' => '活跃留存',
        'exportBankruptcyRetention' => '破产留存',
    ];

    /**
     * 显示操作前缀
     * @var string
     */
    protected $show = 'show';

    /**
     * 导出操作前缀
     * @var string
     */
    protected $export = 'export';

    /**
     * 模型类命名空间
     * @var string
     */
    protected $modelRootNamespace = 'app\common\model\backend\operation\retention\\';

    /**
     * url
     * @var string
     */
    protected $urlRootPath = 'index/operation.Retention/';

    /**
     * 显示留存率
     * @param $func
     * @return mixed|\think\response
     */
    protected function show($func)
    {
        $name = substr($func, strlen($this->show));
        $model = $this->modelRootNamespace . $name;

        if (!$this->request->isAjax()) {
            return $this->fetch('operation/retention/index', [
                'platform' => $model::getPlatform(),
                'channel' => $model::getChanel(),
                'tableClos' => $model::getLayUiTableClos(),
                'showUrl' => url($this->urlRootPath . $func),
                'exportUrl' => url($this->urlRootPath . $this->export . $name),
            ]);
        }

        $data = (new $model)->getTableData($this->request->param());
        return $this->returnToLayuiWithoutPage($data);
    }

    /**
     * new 模型对象
     * @param $func
     * @return string
     */
    protected function getModel($func)
    {
        $model = $this->modelRootNamespace . substr($func, strlen($this->export));
        return new $model;
    }

    /**
     * 响应请求
     * @param $func
     * @return mixed|\think\response
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function _empty($func)
    {
        if (strncmp($func, $this->show, strlen($this->show)) == 0) {
            return $this->show($func);
        } else {
            (new ExportExcel($this->getModel($func), $this->request->param(), $this->excelFileName[$func]))->exportExcel();
        }
    }
}
