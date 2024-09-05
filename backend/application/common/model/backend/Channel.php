<?php

namespace app\common\model\backend;

use app\common\model\BackendModel;

class Channel extends BackendModel
{
    /**
     * layui table clos
     * @return array
     */
    public static function getLayUiTableClos()
    {
        return [
            ['field' => 'id', 'title' => '渠道ID'],
            ['field' => 'channel_name', 'title' => '渠道名'],
        ];
    }

    /**
     * 获取所有渠道
     * @return array
     */
    public static function getAllChannel()
    {
        return self::column('channel_name', 'id');
    }

    /**
     * 获取所有渠道
     * bi存储的是渠道名
     * @return array
     */
    public static function getAllChannelForTask()
    {
        return self::column('id', 'channel_name');
    }

    /**
     * 获取分页数据
     * @param $requestParam
     * @return Channel|\think\Paginator
     */
    public static function getPaginationData($requestParam)
    {
        return self::withSearch(['id', 'channel_name'], $requestParam)->paginate($requestParam['limit']);
    }

    public function searchIdAttr($query, $value)
    {
        if ($value != null) {
            $query->where('id', $value);
        }
    }

    public function searchChannelNameAttr($query, $value)
    {
        if (!empty($value)) {
            $query->where('channel_name', $value);
        }
    }
}
