<?php

namespace app\common\tool\redis\gm;

use app\common\tool\redis\Game;

class Whitelist extends Game
{
    /**
     * key
     * @var string
     */
    protected $key = 'wlsp:v1';

    /**
     * 获取所有uid
     * @return array
     */
    public function getWhitelist()
    {
        return $this->handler->sMembers($this->key);
    }

    /**
     * 是否在白名单中
     * @param $uid
     * @return bool
     */
    public function isInWhitelist($uid)
    {
        return $this->handler->sIsMember($this->key, $uid);
    }

    /**
     * 添加白名单
     * @param $uid
     * @return bool
     */
    public function addWhitelist($uid)
    {
        return $this->handler->sAdd($this->key, $uid) === 1;
    }

    /**
     * 删除白名单
     * @param $uid
     * @return bool
     */
    public function deleteWhitelist($uid)
    {
        return $this->handler->sRem($this->key, $uid) === 1;
    }
}