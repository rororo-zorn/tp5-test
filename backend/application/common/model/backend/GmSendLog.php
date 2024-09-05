<?php

namespace app\common\model\backend;

use app\common\model\BackendModel;

class GmSendLog extends BackendModel
{
    const LOG_TYPE_EMAIL = 1;

    const LOG_TYPE_MARQUEE = 2;

    const LOG_TYPE_GAME_PUSH = 3;
}
