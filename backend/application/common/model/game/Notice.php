<?php

namespace app\common\model\game;

use app\common\model\GameModel;
use app\common\tool\traits\Language;
use app\common\tool\excel\read\GameFunction;
use app\common\tool\pipe\gm\Notice as pipe;

class Notice extends GameModel
{
    use Language;

    /**
     * 纯文字标识
     */
    const WORLD_NOTICE = 1;

    /**
     * 纯图片标识
     */
    const PICTURE_NOTICE = 2;

    /**
     * 默认语言标识
     */
    const DEFAULT_LANGUAGE = 1;

    /**
     * 空json字符串
     */
    const EMPTY_JSON_STR = '[]';

    /**
     * 默认多语言
     * @var
     */
    protected static $defaultLanguage;

    /**
     * 跳转位置
     * @var
     */
    protected static $jumpId;

    /**
     * layui table clos
     * @return array
     */
    public static function getLayUiTableClos()
    {
        return [
            ['field' => 'notice_type', 'title' => '公告类型'],
            ['field' => 'sort', 'title' => '排序'],
            ['field' => 'reddot', 'title' => '小红点'],
            ['field' => 'theme', 'title' => '主题'],
            ['field' => 'theme_tid', 'title' => '主题key'],
            ['field' => 'show_type', 'title' => '展示类型'],
            ['field' => 'title', 'title' => '标题'],
            ['field' => 'title_tid', 'title' => '标题key'],
            ['field' => 'content', 'title' => '内容'],
            ['field' => 'content_tid', 'title' => '内容key'],
            ['field' => 'tail', 'title' => '落款'],
            ['field' => 'tail_tid', 'title' => '落款key'],
            ['field' => 'jump_id', 'title' => '跳转位置'],
            ['field' => 'start_time', 'minWidth' => 160, 'title' => '开始时间'],
            ['field' => 'end_time', 'minWidth' => 160, 'title' => '截止时间'],
            ['field' => '', 'fixed' => 'right', 'width' => 200, 'align' => 'center', 'toolbar' => '#bar', 'title' => '操作'],
        ];
    }

    /**
     * layui table clos
     * @return array
     */
    public static function getPictureTableClos()
    {
        return [
            ['field' => 'picture_url', 'title' => '图片地址'],
            ['field' => 'jump_name', 'title' => '跳转位置'],
            ['field' => '', 'fixed' => 'right', 'width' => 200, 'align' => 'center', 'toolbar' => '#bar', 'title' => '操作'],
        ];
    }

    /**
     * 获取公告类型
     * @return string[]
     */
    public static function getNoticeType()
    {
        return [
            1 => '游戏版本',
            2 => '停服公告',
            3 => '活动公告',
        ];
    }

    /**
     * 获取小红点出现类型
     * @return string[]
     */
    public static function getRedDotAppearType()
    {
        return [
            1 => '只出现一次',
            2 => '每天出现一次',
        ];
    }

    /**
     * 获取展示类型
     * @return string[]
     */
    public static function getShowType()
    {
        return [
            self::WORLD_NOTICE => '纯文字',
            self::PICTURE_NOTICE => '纯图片',
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
                    break;
                }
            }
        }

        return self::$defaultLanguage;
    }

    /**
     * 获取跳转Id
     * @return array
     */
    public static function getJumpId()
    {
        if (empty(self::$jumpId)) {
            self::$jumpId = (new GameFunction())->getFunctionConfig();
        }

        return self::$jumpId;
    }

    /**
     * 获取跳转位置多选下拉框数据
     * @return array
     */
    public static function getMultipleSelectJumpId()
    {
        $data = [];
        foreach (self::getJumpId() as $key => $value) {
            $data[] = [
                'name' => $value,
                'value' => $key,
            ];
        }

        return $data;
    }

    /**
     * 是否是纯文字公告
     * @param $showType
     * @return bool
     */
    public static function isOnlyWorldNotice($showType)
    {
        return $showType == self::WORLD_NOTICE;
    }

    /**
     * 获取分页数据
     * @param $requestParam
     * @return Notice|\think\Paginator
     */
    public static function getPaginationData($requestParam)
    {
        $paginate = self::withSearch(['notice_type', 'show_type'], $requestParam)
            ->order('id', 'desc')
            ->paginate($requestParam['limit']);

        foreach ($paginate->items() as $model) {
            $model->id = $model->getEncryptId();
            $model->notice_type = $model->getNoticeTypeName();
            $model->reddot = $model->getRedDotAppearTypeName();
            $model->theme = $model->getTheme();
            $model->show_type = $model->getShowTypeName();
            $model->title = $model->getTitle();
            $model->content = $model->getContent();
            $model->tail = $model->getTail();
            $model->jump_id = $model->getJumpIdName();
        }

        return $paginate;
    }

    /**
     * 获取加密id
     * @return string|string[]
     */
    protected function getEncryptId()
    {
        return my_openssl_encrypt($this->id);
    }

    /**
     * 获取公告类型名
     * @return string
     */
    protected function getNoticeTypeName()
    {
        $noticeType = self::getNoticeType();
        return $noticeType[$this->notice_type];
    }

    /**
     * 获取小红点出现类型名
     * @return string
     */
    protected function getRedDotAppearTypeName()
    {
        $redDotAppearType = self::getRedDotAppearType();
        return $redDotAppearType[$this->reddot];
    }

    /**
     * 获取主题
     * @return string
     */
    protected function getTheme()
    {
        $value = $this->theme;
        return $value == self::EMPTY_JSON_STR ? '' : $value;
    }

    /**
     * 获取展示类型名
     * @return string
     */
    protected function getShowTypeName()
    {
        $showType = self::getShowType();
        return $showType[$this->show_type];
    }

    /**
     * 获取标题
     * @return string
     */
    protected function getTitle()
    {
        $value = $this->title;
        return empty($value) || $value == self::EMPTY_JSON_STR ? '' : $value;
    }

    /**
     * 获取内容
     * @return mixed|string
     */
    protected function getContent()
    {
        $value = $this->content;
        return empty($value) || $value == self::EMPTY_JSON_STR ? '' : $value;
    }

    /**
     * 获取落款
     * @return mixed|string
     */
    protected function getTail()
    {
        $value = $this->tail;
        return empty($value) || $value == self::EMPTY_JSON_STR ? '' : $value;
    }

    /**
     * 获取跳转位置名
     * @return string
     */
    protected function getJumpIdName()
    {
        $jumpId = json_decode($this->jump_id, true);
        $type = self::getJumpId();
        foreach ($jumpId as $key => $value) {
            $name = isset($type[$value]) ? $type[$value] : '无跳转';
            $jumpId[$key] = $name;
        }

        return empty($jumpId) ? '' : implode_comma($jumpId);
    }

    /**
     * 添加公告
     * @param $requestParam
     * @return bool
     * @throws \think\exception\PDOException
     */
    public function addNotice($requestParam)
    {
        $requestParam = self::getJsonRequestParam($requestParam);

        $this->startTrans();
        if ($this->save($requestParam) && (new pipe())->send()->isSuccess()) {
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
        $requestParam = self::getContentAndJumpId($requestParam);

        $needToJsonField = ['theme', 'title', 'content', 'tail', 'jump_id'];
        foreach ($needToJsonField as $field) {
            $requestParam[$field] = json_encode($requestParam[$field]);
        }

        return $requestParam;
    }

    /**
     * 获取内容和跳转位置
     * @param $requestParam
     * @return mixed
     */
    protected static function getContentAndJumpId($requestParam)
    {
        list('show_type' => $showType, 'content' => $content) = $requestParam;
        if ($showType == self::WORLD_NOTICE) {
            $requestParam['jump_id'] = [];
        } else {
            // 抹掉标题、内容、落款key
            $requestParam['title_tid'] = '';
            $requestParam['content_tid'] = '';
            $requestParam['tail_tid'] = '';

            list($content, $jumpId) = self::getPictureContentAndJumpId($content);
            $requestParam['content'] = $content;
            $requestParam['jump_id'] = $jumpId;
        }

        return $requestParam;
    }

    /**
     * 获取内容和跳转位置
     * @param $data
     * @return array[]
     */
    protected static function getPictureContentAndJumpId($data)
    {
        $content = [];
        $jumpId = [];
        foreach ($data as $key => $value) {
            $content[] = $value['picture_url'];
            $jumpId[] = empty($value['jump_id']) ? '' : intval($value['jump_id']);
        }

        return [$content, $jumpId];
    }

    /**
     * 获取被编辑的公告对象
     * @param $id
     * @return Notice|false
     */
    public static function getEditNotice($id)
    {
        $model = self::getModelById($id);
        if ($model->isEmpty()) {
            return false;
        }

        $model->id = $model->getEncryptId();
        $model->theme = json_decode($model->theme, true);
        $model->title = $model->getEditTitle();
        $model->setEditContent();
        $model->tail = $model->getEditTail();

        return $model;
    }


    /**
     * 获取标题（被编辑）
     * @return array|mixed
     */
    protected function getEditTitle()
    {
        $value = $this->title;
        return empty($value) ? [] : json_decode($value, true);
    }

    /**
     * 设置内容（被编辑）
     */
    protected function setEditContent()
    {
        $value = json_decode($this->content, true);
        if ($this->show_type == self::WORLD_NOTICE) {
            $this->content = $value;
            $this->picture = [];
        } else {
            $jumpId = json_decode($this->jump_id, true);
            $jumpIdList = self::getJumpId();
            $content = [];
            foreach ($value as $k => $v) {
                $jId = $jumpId[$k];
                $content[] = [
                    'id' => $k,
                    'picture_url' => $v,
                    'jump_id' => $jId,
                    'jump_name' => isset($jumpIdList[$jId]) ? $jumpIdList[$jId] : '',
                ];
            }
            $this->picture = $content;
        }
    }

    /**
     * 获取落款（被编辑）
     * @return array|mixed
     */
    protected function getEditTail()
    {
        $value = $this->tail;
        return empty($value) ? [] : json_decode($value, true);
    }

    /**
     * 编辑公告
     * @param $requestParam
     * @return bool
     * @throws \think\exception\PDOException
     */
    public static function editNotice($requestParam)
    {
        $model = self::getModelById($requestParam['id']);
        if ($model->isEmpty()) {
            return false;
        }

        $requestParam = self::getJsonRequestParam($requestParam);

        $model->startTrans();
        if ($model->save($requestParam) && (new pipe())->send()->isSuccess()) {
            $model->commit();
            return true;
        }

        $model->rollback();
        return false;
    }

    /**
     * 删除公告
     * @param $id
     * @return bool
     * @throws \Exception
     */
    public static function deleteNotice($id)
    {
        $model = self::getModelById($id, ['id']);
        return $model->isEmpty() ? false : $model->delete();
    }

    public function searchNoticeTypeAttr($query, $value)
    {
        if (!empty($value)) {
            $query->where('notice_type', $value);
        }
    }

    public function searchShowTypeAttr($query, $value)
    {
        if (!empty($value)) {
            $query->where('show_type', $value);
        }
    }
}
