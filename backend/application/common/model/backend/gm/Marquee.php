<?php

namespace app\common\model\backend\gm;

use app\common\model\backend\Gm;
use app\common\model\backend\GmSendLog;
use app\common\tool\traits\Time;
use app\common\tool\facade\FacadeUser;
use app\common\tool\redis\gm\Marquee as redis;
use app\common\tool\pipe\gm\Marquee as pipe;

class Marquee extends Gm
{
    use Time;

    /**
     * layui table clos
     * @return array
     */
    public static function getLayUiTableClos()
    {
        return [
            ['field' => 'marquee_type', 'title' => '类型'],
            ['field' => 'content', 'title' => '内容'],
            ['field' => 'start_time', 'title' => '开始时间'],
            ['field' => 'end_time', 'title' => '截止时间'],
            ['field' => 'broadcast_start_time', 'title' => '播报开始时间'],
            ['field' => 'broadcast_interval', 'title' => '播报间隔'],
            ['field' => 'broadcast_times', 'title' => '播报次数'],
            ['field' => 'remark', 'title' => '备注'],
            ['field' => 'add_user', 'title' => '添加人'],
            ['field' => 'add_time', 'title' => '添加时间'],
            ['field' => '', 'fixed' => 'right', 'width' => 200, 'align' => 'center', 'toolbar' => '#bar', 'title' => '操作'],
        ];
    }

    /**
     * 获取跑马灯类型
     * @return string[]
     */
    public static function getMarqueeType()
    {
        return [
            1 => '官方跑马灯',
            2 => '活动跑马灯',
            3 => '付费跑马灯',
            4 => '中奖跑马灯',
        ];
    }

    /**
     * 获取分页数据
     * @param $requestParam
     * @return Marquee|\think\Paginator
     */
    public static function getPaginationData($requestParam)
    {
        return self::withSearch(['start_time', 'end_time'], $requestParam)
            ->order('id', 'desc')
            ->paginate($requestParam['limit']);
    }

    /**
     * 添加跑马灯
     * @param $requestParam
     * @return bool
     * @throws \think\exception\PDOException
     */
    public function addMarquee($requestParam)
    {
        $requestParam['add_user'] = FacadeUser::getUserId();
        $requestParam['add_time'] = time();

        $this->startTrans();
        if ($this->save($requestParam) && (new redis($this))->add()) {
            $this->commit();
            return true;
        }

        $this->rollback();
        return false;
    }

    /**
     * 获取被编辑的跑马灯对象
     * @param $id
     * @return Marquee|false
     */
    public static function getEditMarquee($id)
    {
        $model = self::getModelById($id, ['add_user', 'add_time'], true);
        return $model->isEmpty() ? false : $model;
    }

    /**
     * 编辑跑马灯
     * @param $requestParam
     * @return bool
     * @throws \think\exception\PDOException
     */
    public static function editMarquee($requestParam)
    {
        $model = self::getModelById($requestParam['id']);
        if ($model->isEmpty()) {
            return false;
        }

        $model->startTrans();
        if ($model->save($requestParam) && (new redis($model))->edit()) {
            $model->commit();
            return true;
        }

        $model->rollback();
        return false;
    }

    /**
     * 删除跑马灯
     * @param $id
     * @return bool
     * @throws \think\exception\PDOException
     */
    public static function deleteMarquee($id)
    {
        $model = self::getModelById($id, ['id']);
        if ($model->isEmpty()) {
            return false;
        }

        $model->startTrans();
        if ($model->delete() && (new redis($model))->delete()) {
            $model->commit();
            return true;
        }

        $model->rollback();
        return false;
    }

    /**
     * 请求服务器
     * @param $id
     * @return bool
     */
    public static function sendMarqueeToServer($id)
    {
        $model = self::getModelById($id, ['marquee_type', 'content']);
        if ($model->isEmpty()) {
            return false;
        }

        $result = (new pipe($model))->send()->isSuccess();

        $msg = $result ? '发送跑马灯成功' : '发送跑马灯失败';

        (new GmSendLog())->save([
            'log_type' => GmSendLog::LOG_TYPE_MARQUEE,
            'log_content' => $id . '-' . $msg,
            'add_time' => time(),
        ]);

        return $result;
    }

    public function getIdAttr($value)
    {
        return my_openssl_encrypt($value);
    }

    public function getMarqueeTypeAttr($value)
    {
        $marqueeType = self::getMarqueeType();
        return $marqueeType[$value];
    }

    public function setBroadcastStartTimeAttr($value)
    {
        return strtotime($value);
    }

    public function getBroadcastStartTimeAttr($value)
    {
        return date('H:i', $value);
    }

    public function setStartTimeAttr($value)
    {
        return strtotime($value);
    }

    public function getStartTimeAttr($value)
    {
        return date('Y-m-d', $value);
    }

    public function setEndTimeAttr($value)
    {
        return strtotime($value);
    }

    public function getEndTimeAttr($value)
    {
        return date('Y-m-d', $value);
    }

    public function getAddUserAttr($value)
    {
        $users = self::getUser();
        return $users[$value];
    }
}
