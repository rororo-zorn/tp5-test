<?php

namespace app\index\model\gm\game_switch;

use app\index\model\gm\GameSwitch;

class PiggyBank extends GameSwitch
{
    const TITLE = '存钱罐';

    /**
     * 开关类型
     * @var
     */
    protected $switchType = 12;
}