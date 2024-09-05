<?php

namespace app\common\model\backend\operation;

use app\common\model\backend\Operation;
use think\Db;

class NewUser extends Operation
{
    /**
     * 数据表
     * @var string
     */
    protected $table = 'bk_new_user';

    /**
     * layui table clos
     * @return array
     */
    public static function getLayUiTableClos()
    {
        return [
            ['field' => 'daily_time', 'minWidth' => 120, 'title' => '日期'],
            ['field' => 'platform', 'title' => '平台'],
            ['field' => 'channel', 'title' => '渠道'],
            ['field' => 'new_user_count', 'title' => '新增用户数'],
        ];
    }

    /**
     * 获取分页数据
     * @param $requestParam
     * @return NewUser|\think\Paginator
     */
    public static function getPaginationData($requestParam)
    {
        return self::withSearch(['start_time', 'end_time', 'platform', 'channel'], $requestParam)
            ->order('daily_time', 'desc')
            ->paginate($requestParam['limit']);
    }

    /**
     * 获取分页和echarts数据
     * @param $requestParam
     * @return NewUser|array|\think\Paginator
     */
    public static function getPaginationAndEchartsData($requestParam)
    {
        $paginate = self::getPaginationData($requestParam);

        $echarts = [];
        foreach ($paginate->items() as $model) {
            $date = date('Y-m-d', $model->getData('daily_time'));
            $series = $model->platform . '-' . $model->channel;
            $echarts[$series][] = [$date, $model->new_user_count];
        }

        return [
            'paginator' => $paginate,
            'echarts' => $echarts,
        ];
    }

    /**
     * 根据零点时间戳获取数据
     * @param $dailyTime
     * @return array
     */
    public function getDataByDailyTime($dailyTime)
    {
        return Db::table($this->table)
            ->where('daily_time', $dailyTime)
            ->select();
    }
}
