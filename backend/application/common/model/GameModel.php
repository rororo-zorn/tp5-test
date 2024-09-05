<?php

namespace app\common\model;

abstract class GameModel extends BaseModel
{
    /**
     * 数据库连接
     * @var string
     */
    protected $connection = 'game_db';
}