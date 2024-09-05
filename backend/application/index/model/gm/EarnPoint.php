<?php

namespace app\index\model\gm;

use app\common\tool\traits\GameType;
use app\common\tool\redis\gm\EarnPoint as redis;
use app\common\tool\pipe\gm\EarnPoint as pipe;

class EarnPoint
{
    use GameType;

    protected $glue = '-';

    /**
     * 本周配置所在的年份
     * @var
     */
    protected $thisWeekConfigInYear;

    /**
     * 本周
     * @var int
     */
    protected $thisWeek;

    /**
     * 下周配置所在的年份
     * @var
     */
    protected $nextWeekConfigInYear;

    /**
     * 下周
     * @var int
     */
    protected $nextWeek;

    /**
     * layui table clos
     * @return array
     */
    public static function getLayUiTableClos()
    {
        return [
            ['field' => 'date', 'width' => 100, 'title' => '生效日期'],
            ['field' => 'game', 'title' => '机台'],
        ];
    }

    /**
     * 获取生效日期
     * @return string[]
     */
    public static function getEffectiveDate()
    {
        return ['星期一', '星期二', '星期三', '星期四', '星期五', '星期六', '星期日'];
    }

    public function __construct()
    {
        $this->thisWeekConfigInYear = (int)date('Y');
        $this->thisWeek = $this->getWeekNum($this->thisWeekConfigInYear, time());

        $nextWeekMondayTimestamp = $this->getNextWeekMondayTimestamp();
        $this->nextWeekConfigInYear = (int)date('Y', $nextWeekMondayTimestamp);
        $this->nextWeek = $this->getWeekNum($this->nextWeekConfigInYear, $nextWeekMondayTimestamp);
    }

    /**
     * 获取下周星期一的时间戳
     * @return false|int
     */
    protected function getNextWeekMondayTimestamp()
    {
        $todayInWeekNum = date('N');
        $num = 7 - $todayInWeekNum + 1;
        return strtotime("+{$num} day");
    }

    /**
     * 获取周num
     * @param $year
     * @param $timestamp
     * @return int
     */
    protected function getWeekNum($year, $timestamp)
    {
        $firstDayInWeekNum = date('N', strtotime(sprintf('%d-01-01', $year)));    // 每年第一天所在星期中的第几天（1（表示星期一）到7（表示星期天））
        $todayDayInYearNum = date('z', $timestamp) + 1; // 指定天所在年份中的第几天（z：默认为0到365）
        return (int)ceil(($todayDayInYearNum - (7 - $firstDayInWeekNum + 1)) / 7) + 1;
    }

    /**
     * 获取本周配置的哈希key
     * @return string
     */
    protected function getThisWeekHashKey()
    {
        return sprintf('%d%s%d', $this->thisWeekConfigInYear, $this->glue, $this->thisWeek);
    }

    /**
     * 获取下周配置的哈希key
     * @return string
     */
    protected function getNextWeekConfigHashKey()
    {
        return sprintf('%d%s%d', $this->nextWeekConfigInYear, $this->glue, $this->nextWeek);
    }

    /**
     * 是否存在本周配置
     * @return bool
     */
    public function isExistThisWeekConfig()
    {
        $allConfig = (new redis())->getAllConfig();
        krsort($allConfig);

        $isExist = false;
        foreach ($allConfig as $key => $value) {
            list($year, $week) = explode($this->glue, $key);
            if ($year <= $this->thisWeekConfigInYear && $week <= $this->thisWeek) {
                $isExist = true;
                break;
            }
        }

        return $isExist;
    }

    /**
     * 是否存在本周配置
     * @return bool
     */
    public function isExistNextWeekConfig()
    {
        $hashKey = $this->getNextWeekConfigHashKey();
        $config = (new redis())->getConfigByHashKey($hashKey);
        return !empty($config);
    }

    /**
     * 获取本周配置
     * @return array
     */
    public function getThisWeekConfigTableData()
    {
        $config = $this->getThisWeekConfig();
        return $this->getTableNeedData($config);
    }

    /**
     * 获取本周配置
     * @return array|mixed
     */
    public function getThisWeekConfig()
    {
        $allConfig = (new redis())->getAllConfig();
        krsort($allConfig);

        $config = [];
        foreach ($allConfig as $key => $value) {
            list($year, $week) = explode($this->glue, $key);
            if ($year < $this->thisWeekConfigInYear || ($year = $this->thisWeekConfigInYear && $week <= $this->thisWeek)) {
                $config = json_decode($value, true);
                break;
            }
        }

        return $config;
    }

    /**
     * 获取下周配置
     * @return array
     */
    public function getNextWeekConfigTableData()
    {
        $config = $this->getNextWeekConfig();
        return $this->getTableNeedData($config);
    }

    /**
     * 获取下周配置
     * @return array
     */
    public function getNextWeekConfig()
    {
        $hashKey = $this->getNextWeekConfigHashKey();
        return (new redis())->getConfigByHashKey($hashKey);
    }

    /**
     * 获取layui table需要的数据
     * @param $config
     * @return array
     */
    protected function getTableNeedData($config)
    {
        $data = [];
        $day = self::getEffectiveDate();
        foreach ($config as $key => $value) {
            $data[] = [
                'date' => $day[$key],
                'game' => $this->getGameName($value),
            ];
        }

        return $data;
    }

    /**
     * 获取游戏名
     * @param $gameId
     * @return array
     */
    protected function getGameName($gameId)
    {
        $gameName = [];
        $gameType = self::getGameType();
        foreach ($gameId as $id) {
            $gameName[] = $gameType[$id];
        }

        return $gameName;
    }

    /**
     * 设置本周配置
     * @param $config
     * @return bool
     */
    public function setThisWeekConfig($config)
    {
        $hashKey = $this->getThisWeekHashKey();
        return (new redis())->setConfig($hashKey, $config) && (new pipe())->send()->isSuccess();
    }

    /**
     * 设置下周配置
     * @param $config
     * @return bool
     */
    public function setNextWeekConfig($config)
    {
        $hashKey = $this->getNextWeekConfigHashKey();
        return  (new redis())->setConfig($hashKey, $config) && (new pipe())->send()->isSuccess();
    }
}