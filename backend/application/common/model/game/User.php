<?php

namespace app\common\model\game;

use app\common\model\GameModel;

class User extends GameModel
{
    /**
     * 表名
     * @var string
     */
    protected $table = 'user';

    /**
     * 等级
     * @var string[]
     */
    protected static $level = [
        0 => '玩家',
        1 => '卖家',
        2 => '仓库',
        3 => '总代',
    ];

    /**
     * 状态
     * @var string[]
     */
    protected static $status = [
        0 => '正常',
        1 => '禁止',
    ];

    /**
     * 类型
     * @var string[]
     */
    protected static $type_ = [
        0 => '游客',
        1 => '手机注册',
    ];

    /**
     * layui table clos
     * @return array
     */
    public static function getLayUiTableClos()
    {
        return [
            ['field' => 'id', 'fixed' => 'left', 'minWidth' => 120, 'title' => '玩家ID'],
            ['field' => 'device_id', 'minWidth' => 300, 'title' => '设备码'],
            ['field' => 'nick_name', 'minWidth' => 120, 'title' => '昵称'],
            ['field' => 'gold', 'minWidth' => 150, 'title' => '金币数量'],
            ['field' => 'level', 'title' => '等级'],
            ['field' => 'status', 'title' => '状态'],
            ['field' => 'type_', 'title' => '类型'],
            ['field' => 'up_level_id', 'title' => '上级ID'],
            ['field' => 'phone_no', 'minWidth' => 120, 'title' => '手机号'],
            ['field' => 'create_time', 'minWidth' => 170, 'title' => '注册时间'],
        ];
    }

    /**
     * 获取分页数据
     * @param $requestParam
     * @return Notice|\think\Paginator
     */
    public static function getPaginationData($requestParam)
    {
        $field = ['id', 'device_id', 'nick_name', 'gold', 'level', 'status', 'type as type_', 'up_level_id', 'phone_no', 'create_time'];
        $paginate = self::field($field)->withSearch(['id', 'nickname', 'start_time', 'end_time'], $requestParam)
            ->order('id', 'DESC')
            ->paginate($requestParam['limit']);

        return $paginate;
    }

    public function getLevelAttr($value)
    {
        return self::$level[$value];
    }

    public function getStatusAttr($value)
    {
        return self::$status[$value];
    }

    public function getType_Attr($value)
    {
        return self::$type_[$value];
    }

    public function searchIdAttr($query, $value)
    {
        if (!empty($value)) {
            $query->where('id', $value);
        }
    }

    public function searchNicknameAttr($query, $value)
    {
        if (!empty($value)) {
            $query->where('nick_name', $value);
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
