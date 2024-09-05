<?php

namespace app\index\model\gm\game_switch;

use app\index\model\gm\GameSwitch;

class Ad extends GameSwitch
{
    const TITLE = '广告';

    /**
     * 开关类型
     * @var
     */
    protected $switchType = 4;
}