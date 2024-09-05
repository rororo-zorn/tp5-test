<?php

namespace app\index\model\gm\game_switch;

use app\index\model\gm\GameSwitch;

class LeaderBoard extends GameSwitch
{
    const TITLE = '排行榜';

    /**
     * 开关类型
     * @var
     */
    protected $switchType = 6;
}