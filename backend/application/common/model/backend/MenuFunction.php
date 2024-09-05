<?php

namespace app\common\model\backend;

use app\common\model\BackendModel;

class MenuFunction extends BackendModel
{
    /**
     * 根据url查找菜单id
     * @param string $url
     * @return false|mixed
     */
    public static function getMenuIdByUrl($url)
    {
        $model = self::getModelById(['fun_url', $url], ['menu_id']);
        if ($model->isEmpty()) {
            return false;
        }

        return $model->menu_id;
    }
}
