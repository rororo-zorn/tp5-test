<?php

namespace app\index\model\gm\game_switch;

use app\index\model\gm\GameSwitch;

class RedeemCode extends GameSwitch
{
    const TITLE = '兑换码';

    /**
     * 开关类型
     * @var
     */
    protected $switchType = 5;
}