<?php

namespace app\index\model\gm\game_switch;

use app\index\model\gm\GameSwitch;

class BigWin extends GameSwitch
{
    const TITLE = '大奖机制';

    /**
     * 开关类型
     * @var
     */
    protected $switchType = 2;
}