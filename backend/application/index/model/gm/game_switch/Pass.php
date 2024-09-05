<?php

namespace app\index\model\gm\game_switch;

use app\index\model\gm\GameSwitch;

class Pass extends GameSwitch
{
    const TITLE = '通行证';

    /**
     * 开关类型
     * @var
     */
    protected $switchType = 7;
}