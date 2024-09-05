<?php

namespace app\common\tool\traits;

use app\common\tool\excel\read\StarLevel;

trait GameType
{
    /**
     * 游戏类型
     * @var
     */
    protected static $gameType;

    /**
     * 获取游戏类型
     * @return string[]
     */
    public static function getGameType()
    {
        if (self::$gameType == null) {
            $config = (new StarLevel())->getConfig();
            foreach ($config as $item) {
                self::$gameType[$item['id']] = $item['name'];
            }
        }

        return self::$gameType;
    }
}