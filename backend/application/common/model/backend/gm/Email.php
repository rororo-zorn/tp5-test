<?php

namespace app\common\model\backend\gm;

use app\common\model\backend\Gm;
use app\common\tool\traits\Language;
use app\common\tool\traits\Item;
use app\common\model\backend\GmSendLog;
use app\common\tool\facade\FacadeUser;
use app\common\tool\redis\gm\Email as redis;
use app\common\tool\pipe\gm\Email as pipe;

class Email extends Gm
{
    use Language, Item;

    /**
     * 邮件状态-非发送
     */
    const EMAIL_STATUS_NOT_SEND = 1;

    /**
     * 邮件状态-发送成功
     */
    const EMAIL_STATUS_SEND_SUCCESS = 2;

    /**
     * 邮件状态-发送失败
     */
    const EMAIL_STATUS_SEND_FAIL = 3;

    /**
     * 发送对象-指定玩家
     */
    const SEND_TO_DESIGNATED_ID = 1;

    /**
     * 发送对象-全服玩家
     */
    const SEND_TO_ALL = 2;

    /**
     * 默认语言标识
     */
    const DEFAULT_LANGUAGE = 1;

    /**
     * 默认多语言
     * @var
     */
    protected static $defaultLanguage;

    /**
     * 类型自动转换
     * @var string[]
     */
    protected $type = [
        'send_time' => 'timestamp',
        'add_time' => 'timestamp',
    ];

    /**
     * layui table clos
     * @return array
     */
    public static function getLayUiTableClos()
    {
        return [
            ['field' => 'theme', 'title' => '邮件主题'],
            ['field' => 'title', 'title' => '邮件标题'],
            ['field' => 'content', 'title' => '邮件内容'],
            ['field' => 'tail', 'title' => '邮件落款'],
            ['field' => '', 'title' => '附件', 'align' => 'center', 'templet' => '#show-item'],
            ['field' => 'send_type', 'title' => '发送对象'],
            ['field' => 'uid', 'title' => '指定玩家'],
            ['field' => 'send_time', 'minWidth' => 160, 'title' => '发送时间'],
            ['field' => 'status', 'title' => '状态', 'templet' => '#status'],
            ['field' => 'add_user', 'title' => '添加人'],
            ['field' => 'add_time', 'minWidth' => 160, 'title' => '添加时间'],
            ['field' => '', 'fixed' => 'right', 'width' => 200, 'align' => 'center', 'toolbar' => '#bar', 'title' => '操作'],
        ];
    }

    /**
     * 获取发送对象类型
     * @return string[]
     */
    public static function getSendType()
    {
        return [
            self::SEND_TO_DESIGNATED_ID => '指定玩家ID',
            self::SEND_TO_ALL => '全服玩家',
        ];
    }

    /**
     * 获取默认多语言
     * @return mixed
     */
    public static function getDefaultLanguage()
    {
        if (empty(self::$defaultLanguage)) {
            foreach (self::getLanguageList() as $id => $value) {
                if ($value['default'] == self::DEFAULT_LANGUAGE) {
                    self::$defaultLanguage[$id] = $value;
                }
            }
        }

        return self::$defaultLanguage;
    }


    /**
     * 获取id
     * @return mixed
     */
    public function getId()
    {
        return $this->getData('id');
    }

    /**
     * 获取失效事件
     * @return int
     */
    public function getExpiredTime()
    {
        $expired = (int)($this->getData('send_time') - time());
        return $expired > 0 ? $expired : 1;
    }

    /**
     * 是否发送全服邮件
     * @return bool
     */
    public function isSendToAll()
    {
        return $this->getData('send_type') == self::SEND_TO_ALL;
    }

    /**
     * 获取分页数据
     * @param $requestParam
     * @return Email|\think\Paginator
     */
    public static function getPaginationData($requestParam)
    {
        return self::field(['item'], true)
            ->withSearch(['start_time', 'end_time'], $requestParam)
            ->order('id', 'desc')
            ->paginate($requestParam['limit']);
    }

    /**
     * 根据配置id获取附件
     * @param $id
     * @return array|bool
     */
    public static function getItemById($id)
    {
        $model = self::getModelById($id, ['item']);
        return $model->isEmpty() ? false : [
            'item' => $model->item,
            'tableClos' => self::getShowItemTableFields(),
        ];
    }

    /**
     * 添加邮件
     * @param $requestParam
     * @return bool
     * @throws \think\exception\PDOException
     */
    public function addEmail($requestParam)
    {
        $requestParam = self::getJsonRequestParam($requestParam);
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
     * 获取json请求参数
     * @param $requestParam
     * @return mixed
     */
    protected static function getJsonRequestParam($requestParam)
    {
        $needToJsonField = ['theme', 'title', 'content', 'tail'];
        foreach ($needToJsonField as $field) {
            $requestParam[$field] = json_encode($requestParam[$field]);
        }

        return $requestParam;
    }

    /**
     * 根据配置id获取邮件
     * @param $id
     * @return Email|false
     */
    public static function getEditEmail($id)
    {
        $model = self::getModelById($id, ['add_user', 'add_time'], true);
        if ($model->isEmpty() || !$model->isNotSend()) {
            return false;
        }

        $model->theme = json_decode($model->theme, true);
        $model->title = json_decode($model->title, true);
        $model->content = json_decode($model->content, true);
        $model->tail = json_decode($model->tail, true);

        return $model;
    }

    /**
     * 是否未发送
     * @return bool
     */
    protected function isNotSend()
    {
        return $this->email_status == self::EMAIL_STATUS_NOT_SEND;
    }

    /**
     * 编辑邮件
     * @param $requestParam
     * @return bool
     * @throws \think\exception\PDOException
     */
    public static function editEmail($requestParam)
    {
        $model = self::getModelById($requestParam['id']);
        if ($model->isEmpty() || !$model->isNotSend()) {
            return false;
        }

        $requestParam = self::getJsonRequestParam($requestParam);

        $model->startTrans();
        if ($model->save($requestParam) && (new redis($model))->add()) {
            $model->commit();
            return true;
        }

        $model->rollback();
        return false;
    }

    /**
     * 删除邮件
     * @param $id
     * @return bool
     * @throws \think\exception\PDOException
     */
    public static function deleteEmail($id)
    {
        $model = self::getModelById($id, ['id', 'email_status']);
        if ($model->isEmpty() || !$model->isNotSend()) {
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
    public static function sendEmailToServer($id)
    {
        $model = self::getModelById($id);
        if ($model->isEmpty() || !$model->isNotSend()) {
            return false;
        }

        $result = (new pipe($model))->send()->isSuccess();

        if ($result) {
            $status = self::EMAIL_STATUS_SEND_SUCCESS;
            $msg = '发送邮件成功';
        } else {
            $status = self::EMAIL_STATUS_SEND_FAIL;
            $msg = '发送邮件失败';
        }

        $model->save(['email_status' => $status]);

        (new GmSendLog())->save([
            'log_type' => GmSendLog::LOG_TYPE_EMAIL,
            'log_content' => $id . '-' . $msg,
            'add_time' => time(),
        ]);

        return $result;
    }

    public function getIdAttr($value)
    {
        return my_openssl_encrypt($value);
    }

    public function setItemAttr($value)
    {
        return json_encode($value);
    }

    public function getItemAttr($value)
    {
        $value = json_decode($value, true);
        $item = self::getItem();
        foreach ($value as $key => $v) {
            $value[$key]['name'] = $item[$v['id']];
        }

        return $value;
    }

    public function getSendTypeAttr($value)
    {
        $sendType = self::getSendType();
        return $sendType[$value];
    }

    public function setUidAttr($value, $data)
    {
        return $data['send_type'] == self::SEND_TO_DESIGNATED_ID ? $value : '';
    }

    public function getAddUserAttr($value)
    {
        $user = self::getUser();
        return $user[$value];
    }
}
