<?php

namespace app\common\tool\traits;

trait Time
{
    /**
     * 天换秒
     * @var int
     */
    public $dayToSecond = 86400;

    /**
     * 小时转秒
     * @var int
     */
    public $hourToSecond = 3600;

    /**
     * 分钟转秒
     * @var int
     */
    public $minuteToSecond = 60;

    /**
     * 秒转毫秒单位
     * @var int
     */
    public $secondToMillisecond = 1000;

    /**
     * 获取昨天时间戳
     * @return false|int
     */
    public function getYesterdayTimestamp()
    {
        return strtotime('-1 day');
    }

    /**
     * 获取指定时间戳对应日的开始时间戳或毫秒时间戳
     * @param null $timestamp
     * @param false $toMillisecond
     * @return false|int
     */
    public function getStartUnixTimestamp($timestamp = null, $toMillisecond = false)
    {
        if ($timestamp == null) {
            $timestamp = time();
        }

        $timestamp = strtotime(date('Y-m-d 00:00:00', $timestamp));
        if ($toMillisecond) {
            $timestamp *= $this->secondToMillisecond;   // 转为毫秒时间戳
        }

        return $timestamp;
    }

    /**
     * 获取指定时间戳对应日的截止时间戳或毫秒时间戳
     * @param null $timestamp
     * @param false $toMillisecond
     * @return false|int
     */
    public function getEndUnixTimestamp($timestamp = null, $toMillisecond = false)
    {
        if ($timestamp == null) {
            $timestamp = time();
        }

        $timestamp = strtotime(date('Y-m-d 23:59:59', $timestamp));
        if ($toMillisecond) {
            $timestamp *= $this->secondToMillisecond;   // 转为毫秒时间戳
            $timestamp += 999;
        }

        return $timestamp;
    }

    /**
     * 获取当前时刻的毫秒时间戳
     * @return int
     */
    public function getNowMillisecond()
    {
        return intval(microtime(true) * $this->secondToMillisecond);
    }

    /**
     * 毫秒时间戳转时间戳
     * @param $millisecond
     * @return int
     */
    public function millisecondToUnixTimestamp($millisecond)
    {
        return intval($millisecond / $this->secondToMillisecond);
    }
}