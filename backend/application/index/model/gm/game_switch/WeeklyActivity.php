<?php

namespace app\index\model\gm\game_switch;

use app\index\model\gm\GameSwitch;

class WeeklyActivity extends GameSwitch
{
    const TITLE = '周常活动';

    /**
     * 开关类型
     * @var
     */
    protected $switchType = 8;
}