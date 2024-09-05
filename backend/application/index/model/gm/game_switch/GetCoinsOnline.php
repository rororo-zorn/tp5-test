<?php

namespace app\index\model\gm\game_switch;

use app\index\model\gm\GameSwitch;

class GetCoinsOnline extends GameSwitch
{
    const TITLE = '在线领金币';

    /**
     * 开关类型
     * @var
     */
    protected $switchType = 11;
}