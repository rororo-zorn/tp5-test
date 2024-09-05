<?php

namespace app\index\controller;

class Error
{
    /**
     * 默认方法
     * @return string
     */
    public function index()
    {
        return '功能尚未开发完成';
    }

    /**
     * 空操作
     * @return string
     */
    public function _empty()
    {
        return $this->index();
    }
}