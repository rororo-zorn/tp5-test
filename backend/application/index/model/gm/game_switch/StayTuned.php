<?php

namespace app\index\model\gm\game_switch;

use app\index\model\gm\GameSwitch;

class StayTuned extends GameSwitch
{
    const TITLE = '敬请期待';

    /**
     * 开关类型
     * @var
     */
    protected $switchType = 10;
}