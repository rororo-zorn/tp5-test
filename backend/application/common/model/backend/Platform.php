<?php

namespace app\common\model\backend;

use app\common\model\BackendModel;

class Platform extends BackendModel
{
    /**
     * layui table clos
     * @return array
     */
    public static function getLayUiTableClos()
    {
        return [
            ['field' => 'id', 'title' => '平台ID'],
            ['field' => 'platform_name', 'title' => '平台名'],
            ['field' => '', 'fixed' => 'right', 'width' => 100, 'align' => 'center', 'toolbar' => '#bar', 'title' => '操作'],
        ];
    }

    /**
     * 获取所有平台
     * @return array
     */
    public static function getAllPlatform()
    {
        return self::column('platform_name', 'id');
    }

    /**
     * 获取分页数据
     * @param $requestParam
     * @return Platform|\think\Paginator
     */
    public static function getPaginationData($requestParam)
    {
        return self::withSearch(['id', 'platform_name'], $requestParam)->paginate($requestParam['limit']);
    }

    /**
     * 获取被编辑的平台
     * @param $id
     * @return Platform|bool
     */
    public static function getEditPlatform($id)
    {
        $model = self::getModelById($id);
        return $model->isEmpty() ? false : $model;
    }

    /**
     * 编辑平台
     * @param $requestParam
     * @return bool
     */
    public static function editPlatform($requestParam)
    {
        $model = self::getModelById($requestParam['id']);
        return $model->isEmpty() ? false : $model->save($requestParam);
    }

    public function searchIdAttr($query, $value)
    {
        if ($value != null) {
            $query->where('id', $value);
        }
    }

    public function searchPlatformNameAttr($query, $value)
    {
        if (!empty($value)) {
            $query->where('platform_name', $value);
        }
    }
}
