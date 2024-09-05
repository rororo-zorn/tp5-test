<?php

namespace app\index\model\gm\game_switch;

use app\index\model\gm\GameSwitch;

class Game extends GameSwitch
{
    const TITLE = '机台';

    /**
     * 开关类型
     * @var
     */
    protected $switchType = 1;
}