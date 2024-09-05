<?php

namespace app\index\model\gm\game_switch;

use app\index\model\gm\GameSwitch;

class Jackpot extends GameSwitch
{
    const TITLE = 'Jackpot';

    /**
     * 开关类型
     * @var
     */
    protected $switchType = 3;
}