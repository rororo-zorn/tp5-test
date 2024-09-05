<?php

namespace app\common\model\backend\operation;

use app\common\model\backend\Operation;
use think\Db;

class Retention extends Operation
{
    /**
     * 留存率标识
     */
    const RETENTION_MARK = 'retention';

    /**
     * 默认留存率值
     */
    const DEFAULT_RETENTION = -1;

    /**
     * 数据表
     * @var string
     */
    protected $table = 'bk_retention';

    /**
     * 留存率类型
     * @var
     */
    protected $retentionType;

    /**
     * 留存率字段
     * @var string[]
     */
    protected static $retentionField = [
        'retention1',
        'retention2',
        'retention3',
        'retention4',
        'retention5',
        'retention6',
        'retention7',
        'retention8',
        'retention9',
        'retention10',
        'retention11',
        'retention12',
        'retention13',
        'retention14',
        'retention15',
        'retention16',
        'retention17',
        'retention18',
        'retention19',
        'retention20',
        'retention21',
        'retention22',
        'retention23',
        'retention24',
        'retention25',
        'retention26',
        'retention27',
        'retention28',
        'retention29',
        'retention30',
    ];

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
            ['field' => 'count', 'title' => '人数'],
            ['field' => 'retention1', 'title' => '当日留存'],
            ['field' => 'retention2', 'title' => '次日留存'],
            ['field' => 'retention3', 'title' => '3日留存'],
            ['field' => 'retention4', 'title' => '4日留存'],
            ['field' => 'retention5', 'title' => '5日留存'],
            ['field' => 'retention6', 'title' => '6日留存'],
            ['field' => 'retention7', 'title' => '7日留存'],
            ['field' => 'retention8', 'title' => '8日留存'],
            ['field' => 'retention9', 'title' => '9日留存'],
            ['field' => 'retention10', 'title' => '10日留存'],
            ['field' => 'retention11', 'title' => '11日留存'],
            ['field' => 'retention12', 'title' => '12日留存'],
            ['field' => 'retention13', 'title' => '13日留存'],
            ['field' => 'retention14', 'title' => '14日留存'],
            ['field' => 'retention15', 'title' => '15日留存'],
            ['field' => 'retention16', 'title' => '16日留存'],
            ['field' => 'retention17', 'title' => '17日留存'],
            ['field' => 'retention18', 'title' => '18日留存'],
            ['field' => 'retention19', 'title' => '19日留存'],
            ['field' => 'retention20', 'title' => '20日留存'],
            ['field' => 'retention21', 'title' => '21日留存'],
            ['field' => 'retention22', 'title' => '22日留存'],
            ['field' => 'retention23', 'title' => '23日留存'],
            ['field' => 'retention24', 'title' => '24日留存'],
            ['field' => 'retention25', 'title' => '25日留存'],
            ['field' => 'retention26', 'title' => '26日留存'],
            ['field' => 'retention27', 'title' => '27日留存'],
            ['field' => 'retention28', 'title' => '28日留存'],
            ['field' => 'retention29', 'title' => '29日留存'],
            ['field' => 'retention30', 'title' => '30日留存'],
        ];
    }

    /**
     * 根据每日零点时间戳（数组）查找需要更新的数据
     * @param $dailyTime
     * @return array
     */
    public function getDataByDailyTime($dailyTime)
    {
        return Db::table($this->table)
            ->where('daily_time', 'in', $dailyTime)
            ->order('daily_time', 'asc')
            ->select();
    }

    public function getTableData($requestParam)
    {
        $query = Db::table($this->table)->withSearch($this->getWithSearchField(), $requestParam)
            ->where('retention_type', $this->retentionType)
            ->order('id', 'desc');
        self::buildGroupQuery($query, $requestParam);
        $data = $query->select();

        $platform = $this->getPlatformAttr($requestParam['platform']);
        $channel = $this->getChannelAttr($requestParam['channel']);

        foreach ($data as $key => $value) {
            $data[$key]['daily_time'] = date('Y-m-d', $value['daily_time']);
            $data[$key]['platform'] = $platform;
            $data[$key]['channel'] = $channel;
            foreach (self::$retentionField as $field) {
                $data[$key][$field] = $this->calculateRetention($value[$field], $value['count']);
            }
        }

        return $data;
    }

    protected function getWithSearchField()
    {
        return [
            'start_time' => function ($query, $value) {
                call_user_func_array([$this, 'searchStartTimeAttr'], [$query, $value]);
            },
            'end_time' => function ($query, $value) {
                call_user_func_array([$this, 'searchEndTimeAttr'], [$query, $value]);
            },
            'platform' => function ($query, $value) {
                call_user_func_array([$this, 'searchPlatformAttr'], [$query, $value]);
            },
            'channel' => function ($query, $value) {
                call_user_func_array([$this, 'searchChannelAttr'], [$query, $value]);
            },
        ];
    }

    /**
     * 构建分组查询
     * @param $query
     * @param $requestParam
     */
    protected static function buildGroupQuery($query, $requestParam)
    {
        $group = [];

        if (!empty($requestParam['platform'])) {
            $group[] = 'platform';
        }

        if (!empty($requestParam['channel'])) {
            $group[] = 'channel';
        }

        if (count($group) == 2) {
            $query->field(['id', 'platform', 'channel'], true);
            return;
        }

        $group[] = 'daily_time';
        $query->field(self::getGroupQueryField())->group(implode(',', $group));
    }

    /**
     * 获取分组查询字段
     * @return string[]
     */
    protected static function getGroupQueryField()
    {
        $sumField = self::$retentionField;
        $sumField[] = 'count';

        $field = ['daily_time'];
        foreach ($sumField as $item) {
            $field[] = 'SUM(' . $item . ') AS ' . $item;
        }

        return $field;
    }

    /**
     * 计算留存率
     * @param $value
     * @param $count
     * @return float|int|string
     */
    protected function calculateRetention($value, $count)
    {
        return $value <= self::DEFAULT_RETENTION ? '-' : percentage_calc($value, $count);
    }
}
