<?php

namespace app\index\model\gm\game_switch;

use app\index\model\gm\GameSwitch;

class SevenDayLogin extends GameSwitch
{
    const TITLE = '7日登录';

    /**
     * 开关类型
     * @var
     */
    protected $switchType = 13;
}