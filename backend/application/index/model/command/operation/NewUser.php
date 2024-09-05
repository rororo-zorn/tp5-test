<?php

namespace app\index\model\command\operation;

use app\index\model\command\OperationData;
use app\common\model\backend\operation\NewUser as model;

class NewUser
{
    /**
     * @var OperationData
     */
    protected $operationData;

    /**
     * 当前计算日的零点时间戳
     * @var
     */
    protected $dailyTime;

    /**
     * 入库数据
     * @var array
     */
    protected $data = [];

    /**
     * db数据
     * @var array
     */
    protected $dbData;

    public function __construct(OperationData $operationData, $dailyTime)
    {
        $this->operationData = $operationData;
        $this->dailyTime = $dailyTime;
        $this->dbData = (new model())->getDataByDailyTime($dailyTime);
    }

    /**
     * 执行任务
     * @return bool
     */
    public function execute()
    {
        $this->setData();

        try {
            if ((new model())->saveAll($this->data)) {
                return true;
            }
            throw new \Exception();
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * 设置入库数据
     * 补齐当天中途新增的平台渠道数据
     */
    protected function setData()
    {
        foreach ($this->operationData->getPc() as $pc) {
            list($pId, $channel) = $pc;
            $cId = $this->operationData->getCId($channel);

            $find = false;
            foreach ($this->dbData as $dbData) {
                if ($dbData['platform'] == $pId && $dbData['channel'] = $cId) {
                    $this->update($dbData['id'], $pId, $channel);
                    $find = true;
                    break;
                }
            }

            if (!$find) {
                $this->add($pId, $cId, $channel);
            }
        }
    }

    /**
     * 新增
     * @param $pId
     * @param $cId
     * @param $channel
     */
    protected function add($pId, $cId, $channel)
    {
        $this->data[] = [
            'platform' => $pId,
            'channel' => $cId,
            'new_user_count' => $this->operationData->getNewUserNum($this->dailyTime, $pId, $channel),
            'daily_time' => $this->dailyTime,
        ];
    }

    /**
     * 更新
     * @param $id
     * @param $cId
     * @param $channel
     */
    protected function update($id, $cId, $channel)
    {
        $this->data[] = [
            'id' => $id,
            'new_user_count' => $this->operationData->getNewUserNum($this->dailyTime, $cId, $channel),
        ];
    }
}
