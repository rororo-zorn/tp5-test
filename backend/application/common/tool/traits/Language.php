<?php

namespace app\common\tool\traits;

use app\common\tool\excel\read\LanguageList;

trait Language
{
    /**
     * 多语言列表
     * @var
     */
    protected static $languageList;

    /**
     * 获取多语言列表
     * @return array
     */
    public static function getLanguageList()
    {
        if (self::$languageList == null) {
            self::$languageList = (new LanguageList())->getLanguageConfig();
        }

        return self::$languageList;
    }
}