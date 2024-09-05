<?php

namespace app\common\model\game;

use app\common\model\GameModel;

class CouponHistory extends GameModel
{
    /**
     * layui table clos
     * @return array
     */
    public static function getLayUiTableClos()
    {
        return [
            ['field' => 'uid', 'title' => '玩家ID'],
            ['field' => 'coupon_id', 'title' => '兑换码ID'],
            ['field' => 'create_time', 'title' => '领取时间'],
        ];
    }

    /**
     * 获取分页数据
     * @param $param
     * @return Notice|\think\Paginator
     */
    public static function getPaginationData($param)
    {
        $paginate = self::withSearch(['uid', 'coupon_id', 'start_time', 'end_time'], $param)
            ->order('uid', 'DESC')
            ->paginate($param['limit']);

        return $paginate;
    }

    public function searchUidAttr($query, $value)
    {
        if (!empty($value)) {
            $query->where('uid', $value);
        }
    }

    public function searchCouponIdAttr($query, $value)
    {
        if (!empty($value)) {
            $query->where('coupon_id', $value);
        }
    }

    public function searchStartTimeAttr($query, $value)
    {
        if (!empty($value)) {
            $query->where('create_time', '>=', $value);
        }
    }

    public function searchEndTimeAttr($query, $value)
    {
        if (!empty($value)) {
            $query->where('create_time', '<=', $value);
        }
    }
}
