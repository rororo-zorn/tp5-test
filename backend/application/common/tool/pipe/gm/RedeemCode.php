<?php

namespace app\common\tool\pipe\gm;

use app\common\tool\pipe\Pipe;

class RedeemCode extends Pipe
{
    /**
     * 子路由
     * @var string
     */
    protected $subRoute = 'create_coupon';

    public function __construct()
    {
        $this->requestUrl = config('pipe.game_world_server') . '/' . $this->subRoute;
    }

    /**
     * send
     * @param $param
     * @return RedeemCode
     */
    public function send($param)
    {
        $param = $this->getSendData($param);
        return $this->requestServerByHTTP($param);
    }

    /**
     * 获取send data
     * @param $param
     * @return array
     */
    protected function getSendData($param)
    {
        return [
            'type' => intval($param['type']),
            'count' => intval($param['count']),
            'rewards' => [
                'list' => $this->getRewardsList($param['item']),
            ],
            'startTime' => strtotime($param['startTime']),
            'endTime' => strtotime($param['endTime']),
        ];
    }

    /**
     * 获取奖励列表
     * @param $item
     * @return array
     */
    protected function getRewardsList($item)
    {
        $rewards = [];
        foreach ($item as $value) {
            $rewards[] = [
                'id' => intval($value['id']),
                'amount' => intval($value['amount']),
            ];
        }

        return $rewards;
    }
}