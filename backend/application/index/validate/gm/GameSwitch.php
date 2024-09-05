<?php

namespace app\index\validate\gm;

use app\common\validate\BaseValidate;

class GameSwitch extends BaseValidate
{
    /**
     * 定义验证规则
     */
    protected $rule = [
        'state' => ['require', 'number',],
        'id' => ['require', 'validateSwitch'],
    ];

    /**
     * 定义错误信息
     */
    protected $message = [
        'state.require' => '非法参数',
        'state.number' => '非法参数',
        'id.require' => '非法参数',
    ];

    /**
     * 模型
     * @var
     */
    protected $model;

    /**
     * 设置模型
     * @param $model
     * @return $this
     */
    public function setModel($model)
    {
        $this->model = $model;
        return $this;
    }

    /**
     * 验证开关
     * @param $value
     * @param $rule
     * @param $data
     * @return mixed
     */
    protected function validateSwitch($value, $rule, $data)
    {
        return $this->model->validateSwitch($value, $data['state']);
    }
}
