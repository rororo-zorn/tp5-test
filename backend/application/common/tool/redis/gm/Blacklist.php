<?php

namespace app\common\tool\redis\gm;

use app\common\tool\redis\Game;

class Blacklist extends Game
{
    /**
     * key
     * @var string
     */
    protected $key = 'blsp:v1';

    /**
     * 获取黑名单
     * @return array
     */
    public function getBlacklist()
    {
        return $this->handler->sMembers($this->key);
    }

    /**
     * 是否在黑名单中
     * @param $uid
     * @return bool
     */
    public function isInBlacklist($uid)
    {
        return $this->handler->sIsMember($this->key, $uid);
    }

    /**
     * 添加黑名单
     * @param $uid
     * @return bool
     */
    public function addBlacklist($uid)
    {
        return $this->handler->sAdd($this->key, $uid) === 1;
    }

    /**
     * 删除黑名单
     * @param $uid
     * @return bool
     */
    public function deleteBlacklist($uid)
    {
        return $this->handler->sRem($this->key, $uid) === 1;
    }
}