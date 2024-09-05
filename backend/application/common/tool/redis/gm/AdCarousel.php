<?php

namespace app\common\tool\redis\gm;

use app\common\tool\redis\Game;

class AdCarousel extends Game
{
    /**
     * key
     * @var string
     */
    protected $key = 'backend_config:rotation';

    /**
     * 获取广告轮播图配置
     * @return array
     */
    public function getAdConfig()
    {
        $adConf = $this->handler->hGetAll($this->key);
        foreach ($adConf as $key => $value) {
            $adConf[$key] = json_decode($value, true);
        }

        return $adConf;
    }

    /**
     * 编辑广告轮播图配置
     * @param $data
     * @return bool
     */
    public function editAdConfig($data)
    {
        $hashKey = $data['id'];
        $hashData = json_encode([
            'id' => intval($data['id']),
            'startTime' => $data['startTime'],
            'endTime' => $data['endTime'],
            'enable' => $data['enable'] ? true : false,
        ]);

        return $this->handler->hSet($this->key, $hashKey, $hashData) !== false;
    }

    /**
     * 删除广告轮播图配置
     * @param $hashKey
     * @return bool
     */
    public function deleteAdConfig($hashKey)
    {
        return $this->handler->hDel($this->key, $hashKey) != false; // 实测hashtable或者key不存在时，返回为0
    }
}