<?php

namespace app\common\model\backend\gm;

use app\common\model\backend\Gm;
use app\common\model\backend\GmSendLog;
use app\common\tool\traits\Time;
use app\common\tool\facade\FacadeUser;
use app\common\tool\redis\gm\GamePush as redis;
use app\common\tool\pipe\gm\GamePush as pipe;

class GamePush extends Gm
{
    use Time;

    /**
     * 发送对象-广播
     */
    const SEND_TO_ALL = 0;

    /**
     * 发送对象-指定玩家
     */
    const SEND_TO_DESIGNATED_ID = 1;

    /**
     * layui table clos
     * @return array
     */
    public static function getLayUiTableClos()
    {
        return [
            ['field' => 'push_type', 'title' => '推送类型'],
            ['field' => 'send_type', 'title' => '发送对象'],
            ['field' => 'uid', 'title' => '指定玩家'],
            ['field' => 'title', 'title' => '推送标题'],
            ['field' => 'content', 'title' => '推送内容'],
            ['field' => 'image', 'title' => '推送图片'],
            ['field' => 'start_time', 'title' => '生效开始时间'],
            ['field' => 'end_time', 'title' => '生效截止时间'],
            ['field' => 'push_start_time', 'title' => '推送开始时间'],
            ['field' => 'push_interval', 'title' => '推送间隔'],
            ['field' => 'push_interval_unit', 'title' => '间隔单位'],
            ['field' => 'push_times', 'title' => '推送次数'],
            ['field' => 'add_user', 'title' => '添加人'],
            ['field' => 'add_time', 'title' => '添加时间'],
            ['field' => '', 'fixed' => 'right', 'width' => 200, 'align' => 'center', 'toolbar' => '#bar', 'title' => '操作'],
        ];
    }

    /**
     * 获取推送类型
     * @return string[]
     */
    public static function getPushType()
    {
        return [
            0 => 'fcm',
            1 => 'apns'
        ];
    }

    /**
     * 获取发送对象
     * @return string[]
     */
    public static function getSendType()
    {
        return [
            self::SEND_TO_ALL => '广播',
            self::SEND_TO_DESIGNATED_ID => '指定玩家ID'
        ];
    }

    /**
     * 获取推送间隔单位
     * @return string[]
     */
    public static function getPushIntervalUnit()
    {
        return [
            1 => '分钟',
            2 => '小时',
            3 => '天',
        ];
    }

    /**
     * 获取分页数据
     * @param $requestParam
     * @return GamePush|\think\Paginator
     */
    public static function getPaginationData($requestParam)
    {
        return self::withSearch(['start_time', 'end_time'], $requestParam)
            ->order('id', 'desc')
            ->paginate($requestParam['limit']);
    }

    /**
     * 添加游戏推送
     * @param $requestParam
     * @return bool
     * @throws \think\exception\PDOException
     */
    public function addGamePush($requestParam)
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
     * 获取被编辑的游戏推送对象
     * @param $id
     * @return GamePush|bool
     */
    public static function getEditGamePush($id)
    {
        $model = self::getModelById($id, ['add_user', 'add_time'], true);
        return $model->isEmpty() ? false : $model;
    }

    /**
     * 编辑游戏推送
     * @param $requestParam
     * @return bool
     * @throws \think\exception\PDOException
     */
    public static function editGamePush($requestParam)
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
     * 删除游戏推送
     * @param $id
     * @return bool
     * @throws \think\exception\PDOException
     */
    public static function deleteGamePush($id)
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

    /***
     * 请求服务器
     * @param $id
     * @return bool
     */
    public static function sendGamePushToServer($id)
    {
        $model = self::getModelById($id, ['push_type', 'send_type', 'uid', 'title', 'content', 'image']);
        if ($model->isEmpty()) {
            return false;
        }

        $result = (new pipe($model))->send()->isSuccess();

        $msg = $result ? '发送游戏推送成功' : '发送游戏推送失败';

        (new GmSendLog())->save([
            'log_type' => GmSendLog::LOG_TYPE_GAME_PUSH,
            'log_content' => $id . '-' . $msg,
            'add_time' => time(),
        ]);

        return $result;
    }

    /**
     * 获取send uid
     * @return array|false|string[]
     */
    public function getSendUid()
    {
        $uid = [];

        if (!empty($this->uid)) {
            $uid = explode_comma($this->uid);

            array_walk($uid, function (&$item) {
                $item = intval($item);
            });
        }

        return $uid;
    }

    public function getIdAttr($value)
    {
        return my_openssl_encrypt($value);
    }

    public function getPushTypeAttr($value)
    {
        $type = self::getPushType();
        return $type[$value];
    }

    public function getSendTypeAttr($value)
    {
        $type = self::getSendType();
        return $type[$value];
    }

    public function setPushStartTimeAttr($value)
    {
        return strtotime($value);
    }

    public function getPushStartTimeAttr($value)
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

    public function getPushIntervalUnitAttr($value)
    {
        $unit = self::getPushIntervalUnit();
        return $unit[$value];
    }

    public function getAddUserAttr($value)
    {
        $users = self::getUser();
        return $users[$value];
    }
}
