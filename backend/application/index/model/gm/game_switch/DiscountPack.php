<?php

namespace app\index\model\gm\game_switch;

use app\index\model\gm\GameSwitch;

class DiscountPack extends GameSwitch
{
    const TITLE = '折扣礼包';

    /**
     * 开关类型
     * @var
     */
    protected $switchType = 9;
}