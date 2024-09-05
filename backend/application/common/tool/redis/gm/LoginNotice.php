<?php

namespace app\common\tool\redis\gm;

use app\common\tool\redis\Game;

class LoginNotice extends Game
{
    /**
     * key格式
     * @var string
     */
    protected $keyFormat = 'lnk:%s:v1';

    /**
     * redis key
     * @var array
     */
    protected $key;

    public function __construct($languageList)
    {
        parent::__construct();
        $this->key = $this->getkey($languageList);
    }

    /**
     * 获取key
     * @param $languageList
     * @return array|int|string
     */
    protected function getKey($languageList)
    {
        $key = [];
        foreach ($languageList as $lang) {
            $key[$lang] = sprintf($this->keyFormat, $lang);
        }

        return $key;
    }

    /**
     * 获取公告内容
     * @return array
     */
    public function getContent()
    {
        $content = [];
        foreach ($this->key as $lang => $key) {
            $data = $this->handler->get($key);
            $content[$lang] = $data === false ? '' : $data;
        }

        return $content;
    }

    /**
     * 设置公告内容
     * @param $content
     * @return bool
     */
    public function setContent($content)
    {
        foreach ($this->key as $lang => $key) {
            $this->handler->set($key, $content[$lang]);
        }

        return true;
    }

    /**
     * 删除公告内容
     * @return int
     */
    public function deleteContent()
    {
        return $this->handler->del(array_values($this->key));
    }
}