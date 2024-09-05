<?php

namespace app\common\model\backend;

use app\common\tool\traits\Platform;
use app\common\tool\traits\Channel;
use app\common\model\BackendModel;

class Operation extends BackendModel
{
    use Platform;
    use Channel;

    /**
     * 时间字段显示格式
     * @var string
     */
    protected $dateFormat = 'Y-m-d';

    /**
     * 类型自动转换
     * @var string[]
     */
    protected $type = [
        'daily_time' => 'timestamp',
    ];

    public function getPlatformAttr($value)
    {
        $platform = self::getPlatform();
        return $platform[$value] ?? '全平台';
    }

    public function getChannelAttr($value)
    {
        $channel = self::getChanel();
        return $channel[$value] ?? '全渠道';
    }

    public function searchStartTimeAttr($query, $value)
    {
        if (!empty($value)) {
            $query->where('daily_time', '>=', strtotime($value));
        }
    }

    public function searchEndTimeAttr($query, $value)
    {
        if (!empty($value)) {
            $query->where('daily_time', '<=', strtotime($value));
        }
    }

    public function searchPlatformAttr($query, $value)
    {
        if ($value !== '') {
            $query->where('platform', intval($value));
        }
    }

    public function searchChannelAttr($query, $value)
    {
        if ($value !== '') {
            $query->where('channel', intval($value));
        }
    }
}