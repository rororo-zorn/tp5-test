<?php

namespace app\common\model\game;

use app\common\model\GameModel;
use app\common\tool\traits\Item;
use app\common\tool\pipe\gm\RedeemCode as pipe;

class Coupon extends GameModel
{
    use Item;

    /**
     * 领取类型
     * @var string[]
     */
    protected static $exchangeType = [
        0 => '可领取一次',
        1 => '可一直领取，单个账号仅领取一次',
    ];

    /**
     * 状态
     * @var string[]
     */
    protected static $status = [
        0 => [
            'status' => true,
            'msg' => '可领取'
        ],
        1 => [
            'status' => false,
            'msg' => '不可领取'
        ],
    ];

    /**
     * layui table clos
     * @return array
     */
    public static function getLayUiTableClos()
    {
        return [
            ['field' => 'id', 'title' => '兑换码ID'],
            ['field' => '', 'title' => '奖励', 'align' => 'center', 'templet' => '#show-item'],
            ['field' => 'start_time', 'minWidth' => 170, 'title' => '开始时间'],
            ['field' => 'end_time', 'minWidth' => 170, 'title' => '截至时间'],
            ['field' => 'type', 'title' => '领取类型'],
            ['field' => 'status', 'title' => '状态', 'templet' => '#status'],
            ['field' => 'create_time', 'minWidth' => 170, 'title' => '创建时间'],
        ];
    }

    /**
     * 获取领取类型
     * @return string[]
     */
    public static function getExchangeType()
    {
        return self::$exchangeType;
    }

    /**
     * 获取领取状态
     * @return array[]|string[]
     */
    public static function getStatus()
    {
        return self::$status;
    }

    /**
     * 获取分页数据
     * @param $param
     * @return Coupon|\think\Paginator
     */
    public static function getPaginationData($param)
    {
        $paginate = self::withSearch(['id', 'start_time', 'end_time', 'exchange_type', 'status'], $param)
            ->order('create_time', 'DESC')
            ->paginate($param['limit']);

        return $paginate;
    }

    /**
     * 根据配置id获取奖励
     * @param $id
     * @return array|bool
     */
    public static function getItemById($id)
    {
        $model = self::getModelById($id, ['reward']);
        return $model->isEmpty() ? false : [
            'item' => $model->reward,
            'tableClos' => self::getShowItemTableFields(),
        ];
    }

    /**
     * 添加兑换码
     * @param $param
     * @return bool
     */
    public static function addCoupon($param)
    {
        return (new pipe())->send($param)->isSuccess();
    }

    public function getRewardAttr($value)
    {
        $value = json_decode($value, true);
        $value = $value['list'];
        $item = self::getItem();
        foreach ($value as $key => $v) {
            $value[$key]['name'] = $item[$v['id']];
        }

        return $value;
    }

    public function getTypeAttr($value)
    {
        return self::$exchangeType[$value];
    }

    public function getStatusAttr($value)
    {
        return self::$status[$value]['status'];
    }

    public function searchIdAttr($query, $value)
    {
        if (!empty($value)) {
            $query->where('id', $value);
        }
    }

    public function searchStartTimeAttr($query, $value)
    {
        if (!empty($value)) {
            $query->where('start_time', '>=', $value);
        }
    }

    public function searchEndTimeAttr($query, $value)
    {
        if (!empty($value)) {
            $query->where('end_time', '<=', $value);
        }
    }

    public function searchExchangeTypeAttr($query, $value)
    {
        if (!empty($value) || $value == '0') {
            $query->where('type', $value);
        }
    }

    public function searchStatusAttr($query, $value)
    {
        if (!empty($value) || $value == '0') {
            $query->where('status', $value);
        }
    }
}
