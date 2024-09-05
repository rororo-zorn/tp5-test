<?php

namespace app\common\tool\redis\gm;

use app\common\tool\redis\Expire;
use app\common\tool\traits\Time;

class GamePush extends Expire
{
    use Time;

    /**
     * sub key
     * @var string
     */
    protected $subKey = 'push';

    /**
     * redis key
     * @var
     */
    protected $key;

    protected $model;

    /**
     * @param \app\common\model\backend\gm\GamePush $model
     */
    public function __construct($model)
    {
        parent::__construct();

        $this->model = $model;
    }

    /**
     * 添加
     * @return bool
     */
    public function add()
    {
        $this->key = $this->getAddKey();

        foreach ($this->key as $key) {
            list($key, $expiredTime) = $key;
            $this->handler->setex($key, $expiredTime, 1);
        }

        return true;
    }

    /**
     * 获取添加key
     * @return array
     */
    protected function getAddKey()
    {
        $id = $this->model->getData('id');
        $startTime = $this->model->getData('start_time');
        $endTime = $this->model->getData('end_time');
        $pushStartTime = $this->model->getData('push_start_time') - $startTime;
        $pushInterval = $this->getPushInterval($this->model->push_interval, $this->model->getData('push_interval_unit'));
        $pushTimes = $this->model->push_times;

        $key = [];

        // 计算key和expire
        $endTime = $this->getEndUnixTimestamp($endTime);
        for ($i = $startTime; $i <= $endTime;) {
            $time = $i + $pushStartTime; // 当天首次播报时间
            if ($time > time()) {
                $key[] = $this->getKeyAndExpiredTime($id, $time);
            }

            // 处理间隔时间和播报次数
            if ($pushTimes > 1) {
                $todayEndTime = $this->getEndUnixTimestamp($i);
                for ($j = 1; $j <= $pushTimes - 1; $j++) {
                    $time += $pushInterval;
                    if ($time <= $todayEndTime && $time > time()) {
                        $key[] = $this->getKeyAndExpiredTime($id, $time);
                    } else {
                        break;
                    }
                }
            }

            $i += $this->dayToSecond;   // 加一天
        }

        return $key;
    }

    /**
     * 获取推送间隔（秒）
     * @param $pushInterval
     * @param $intervalUnit
     * @return float|int
     */
    protected function getPushInterval($pushInterval, $intervalUnit)
    {
        $unit = [
            1 => 60,
            2 => 3600,
            3 => 86400,
        ];

        return $pushInterval * $unit[$intervalUnit];
    }

    /**
     * 获取key和expire
     * @param $id
     * @param $time
     * @return array
     */
    protected function getKeyAndExpiredTime($id, $time)
    {
        $key = sprintf('%s:%d:%d', $this->subKey, $id, $time);

        $expiredTime = (int)($time - time());
        $expiredTime = $expiredTime > 0 ? $expiredTime : 1;

        return [$key, $expiredTime];
    }

    /**
     * 先删除，再创建
     * @return bool
     */
    public function edit()
    {
        $this->delete();
        return $this->add();
    }

    /**
     * 删除
     * @return int
     */
    public function delete()
    {
        $this->key = $this->getDeleteKey();

        $this->handler->setOption(\Redis::OPT_SCAN, \Redis::SCAN_RETRY);
        $it = null;
        while ($keys = $this->handler->scan($it, $this->key . '*', 100)) {
            call_user_func_array([$this->handler, 'del'], $keys);
        }

        return true;
    }

    /**
     * 获取删除时的key
     * @return string
     */
    protected function getDeleteKey()
    {
        return sprintf('%s:%d', $this->subKey, $this->model->getData('id'));
    }
}