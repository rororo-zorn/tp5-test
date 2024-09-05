<?php

namespace app\index\tool\command;

use think\console\command;
use think\console\Input;
use think\console\Output;
use app\index\model\command\OperationData as task;

class OperationData extends Command
{
    /**
     * 配置指令
     */
    protected function configure()
    {
        $this->setName('OperationData')->setDescription('运营数据');
    }

    /**
     * 执行任务
     * @param Input $input
     * @param Output $output
     */
    protected function execute(Input $input, Output $output)
    {
        (new task())->execute();
    }
}