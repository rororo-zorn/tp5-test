<?php

namespace app\index\validate\gm;

use app\common\validate\BaseValidate;
use app\common\model\game\Notice as model;

class Notice extends BaseValidate
{
    /**
     * 定义验证规则
     */
    protected $rule = [
        'notice_type' => ['require', 'number', 'validateNoticeType'],
        'sort' => ['require', 'number', 'between:1,999'],
        'reddot' => ['require', 'number', 'validateLegalRedDot'],
        'show_type' => ['require', 'number', 'validateShowType', 'validateNotice'],
        'start_time' => ['require', 'date', 'dateFormat:Y-m-d H:i:s'],
        'end_time' => ['require', 'date', 'dateFormat:Y-m-d H:i:s', 'validateEndTime'],
        'token_add_notice' => ['tokenRequire', 'token:token_add_notice'],
        'id' => ['require', 'number'],
        'token_edit_notice' => ['tokenRequire', 'token:token_edit_notice'],
    ];

    /**
     * 定义错误信息
     */
    protected $message = [
        'notice_type.require' => '请选择公告类型',
        'notice_type.number' => '公告类型，非法参数',
        'sort.require' => '请输入排序',
        'sort.number' => '排序，非法参数',
        'sort.between' => '排序范围：1~999',
        'reddot.require' => '请选择小红点',
        'reddot.number' => '小红点，非法参数',
        'show_type.require' => '请选择展示类型',
        'show_type.number' => '展示类型，非法参数',
        'start_time.require' => '请输入开始时间',
        'start_time.date' => '开始时间，非法格式',
        'start_time.dateFormat' => '开始时间，非法格式',
        'end_time.require' => '截止时间，非法格式',
        'end_time.date' => '截止时间，非法格式',
        'end_time.dateFormat' => '截止时间，非法格式',
        'jump_id.array' => '跳转位置，非法参数',
        'id.require' => '非法参数',
        'id.number' => '非法参数',
    ];

    /**
     * 定义验证场景
     * @var array
     */
    protected $scene = [
        'add' => ['notice_type', 'sort', 'reddot', 'show_type', 'start_time', 'end_time', 'token_add_notice'],
        'edit' => ['id'],
        'doEdit' => ['id', 'notice_type', 'sort', 'reddot', 'show_type', 'jump_id', 'start_time', 'end_time', 'token_edit_notice'],
        'doDelete' => ['id'],
    ];

    /**
     * 验证公告类型
     * @param $value
     * @return bool|string
     */
    protected function validateNoticeType($value)
    {
        return in_array($value, array_keys(model::getNoticeType())) ?: '公告类型，非法参数';
    }

    /**
     * 验证小红点出现方式
     * @param $value
     * @return bool|string
     */
    protected function validateLegalRedDot($value)
    {
        return in_array($value, array_keys(model::getRedDotAppearType())) ?: '小红点，非法参数';
    }

    /**
     * 验证公告展示类型
     * @param $value
     * @return bool|string
     */
    protected function validateShowType($value)
    {
        return in_array($value, array_keys(model::getShowType())) ?: '展示类型，非法参数';
    }

    /**
     * 验证公告
     * 验证主题、标题、内容、落款
     * @param $value
     * @param $rule
     * @param $data
     * @return bool|string
     */
    protected function validateNotice($value, $rule, $data)
    {
        $result = $this->validateTheme($data['theme_tid'], $data['theme']);
        if ($result !== true) {
            return $result;
        }

        if ($value == model::PICTURE_NOTICE) {
            return $this->validateOnlyPictureContent($data['content']);
        }

        $result = $this->validateTitle($data['title_tid'], $data['title']);
        if ($result !== true) {
            return $result;
        }

        $result = $this->validateOnlyWorldContent($data['content_tid'], $data['content']);
        if ($result !== true) {
            return $result;
        }

        $result = $this->validateTail($data['tail_tid'], $data['tail']);
        if ($result !== true) {
            return $result;
        }

        return true;
    }

    /**
     * 验证主题
     * @param $themeKey
     * @param $theme
     * @return bool|string
     */
    protected function validateTheme($themeKey, $theme)
    {
        if (empty($themeKey) && empty($theme)) {
            return '主题或主题key至少填一个';
        }

        if (empty($themeKey)) {
            $result = $this->validateItem($theme);
            if ($result !== true) {
                return sprintf($result, '主题');
            }
        }

        return true;
    }

    /**
     * 验证标题
     * @param $titleKey
     * @param $title
     * @return bool|string
     */
    protected function validateTitle($titleKey, $title)
    {
        if (empty($titleKey) && empty($title)) {
            return '标题或标题key至少填一个';
        }

        if (empty($titleKey)) {
            $result = $this->validateItem($title);
            if ($result !== true) {
                return sprintf($result, '标题');
            }
        }

        return true;
    }

    /**
     * 验证纯文字内容
     * @param $contentKey
     * @param $content
     * @return bool|string
     */
    protected function validateOnlyWorldContent($contentKey, $content)
    {
        if (empty($contentKey) && empty($content)) {
            return '内容或内容key至少填一个';
        }

        if (empty($contentKey)) {
            $result = $this->validateItem($content);
            if ($result !== true) {
                return sprintf($result, '内容');
            }
        }

        return true;
    }

    /**
     * 验证纯图片内容
     * @param $content
     * @return bool|string
     */
    protected function validateOnlyPictureContent($content)
    {
        if (empty($content)) {
            return '请添加图片';
        }

        foreach ($content as $key => $value) {
            $id = $key + 1;
            list('picture_url' => $url, 'jump_id' => $jumpId) = $value;

            if (!$this->is($url, 'url')) {
                return "第{$id}个图片链接格式错误";
            }

            if (!empty($jumpId) && !in_array($jumpId, array_keys(model::getJumpId()))) {
                return "第{$id}个跳转位置错误";
            }
        }

        return true;
    }

    /**
     * 验证落款
     * @param $tailKey
     * @param $tail
     * @return bool|string
     */
    protected function validateTail($tailKey, $tail)
    {
        if (empty($tailKey) && empty($tail)) {
            return '落款或落款key至少填一个';
        }

        if (empty($tailKey)) {
            $result = $this->validateItem($tail);
            if ($result !== true) {
                return sprintf($result, '落款');
            }
        }

        return true;
    }

    /**
     * 验证项
     * @param $item
     * @return bool|string
     */
    protected function validateItem($item)
    {
        $languageList = model::getLanguageList();

        if (!empty(array_diff_key($item, $languageList))) {
            return '%s，非法参数';
        }

        // 默认语言必填
        foreach (model::getDefaultLanguage() as $id => $value) {
            if (!isset($item[$id])) {
                $lang = $value['name'];
                return "请输入{$lang}%s";
            }
        }

        return true;
    }

    /**
     * 验证截止时间是否大于开始时间
     * @param $value
     * @param $rule
     * @param $data
     * @return bool|string
     */
    protected function validateEndTime($value, $rule, $data)
    {
        $startTime = strtotime($data['start_time']);
        return strtotime($value) > $startTime ?: '截止时间必须大于开始时间';
    }
}
