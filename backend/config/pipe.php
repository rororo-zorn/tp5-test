<?php
// +----------------------------------------------------------------------
// | 通信设置
// +----------------------------------------------------------------------

return [
    // 通信地址
    'game_center_server' => Env::get('pipe.game_center_server'),    // 游戏center服通信地址
    'game_world_server' => Env::get('pipe.game_world_server'),    // 游戏world服通信地址
    'game_push_server' => Env::get('pipe.game_push_server'),    // 游戏push服通信地址
];
