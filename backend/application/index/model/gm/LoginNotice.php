<?php

namespace app\index\model\gm;

use app\common\tool\traits\Language;
use app\common\tool\redis\gm\LoginNotice as redis;

class LoginNotice
{
    use Language;

    /**
     * 内容
     * @var
     */
    protected $content;

    public function __construct()
    {
        $languageList = array_keys(self::getLanguageList());
        $this->content = (new redis($languageList))->getContent();
    }

    /**
     * get魔术方法
     * @param $name
     * @return mixed
     */
    public function __get($name)
    {
        return $this->content[$name];
    }

    /**
     * 添加公告
     * @param $content
     * @return bool
     */
    public static function addNotice($content)
    {
        $languageList = array_keys(self::getLanguageList());
        return (new redis($languageList))->setContent($content);
    }

    /**
     * 删除公告
     * @return int
     */
    public static function deleteNotice()
    {
        $languageList = array_keys(self::getLanguageList());
        return (new redis($languageList))->deleteContent();
    }
}