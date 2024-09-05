<?php

namespace app\common\model\backend;

use app\common\model\BackendModel;

class Menu extends BackendModel
{
    /**
     * layui table clos
     * @return array
     */
    public static function getLayUiTableClos()
    {
        return [
            ['field' => 'menu_name', 'title' => '菜单名称'],
            ['field' => 'parent_name', 'title' => '父级菜单'],
            ['field' => 'menu_sort', 'title' => '菜单排序'],
            ['field' => 'add_time', 'title' => '添加时间'],
            ['field' => '', 'fixed' => 'right', 'width' => 100, 'align' => 'center', 'toolbar' => '#bar', 'title' => '操作'],
        ];
    }

    /**
     * 根据url查找菜单id
     * @param string $url
     * @return false|mixed
     */
    public static function getMenuIdByUrl($url)
    {
        $model = self::getModelById(['menu_url', $url], ['id']);
        if ($model->isEmpty()) {
            return false;
        }

        return $model->id;
    }

    /**
     * 获取zTree key
     * @return array
     */
    public static function getZTreeKey()
    {
        return [
            'idKey' => 'id',
            'pIdKey' => 'parent_id',
            'rootPid' => 0,
        ];
    }

    /**
     * 获取所有菜单id
     * @return array
     */
    public static function getAllMenuId()
    {
        return self::column('id');
    }

    /**
     * 获取菜单
     * @param false $isAll
     * @param false $isTree
     * @param array $menuId
     * @return array|array[]|\array[][]
     */
    public static function getMenu($isAll = false, $isTree = false, $menuId = [])
    {
        $field = ['id', 'parent_id', 'menu_name', 'menu_url', 'menu_icon'];
        if ($isTree) {
            $field = ['id', 'parent_id', 'menu_name'];
        }

        $query = self::field($field)->order('menu_sort');
        if (!$isAll) {
            $query->whereIn('id', $menuId);
        }

        $nodes = $query->select()->toArray();

        return $isTree ? $nodes : self::getMenuBar($nodes);
    }

    /**
     * 获取菜单栏
     * @param $nodes
     * @return array
     */
    protected static function getMenuBar($nodes)
    {
        $items = [];
        foreach ($nodes as $node) {
            $items[$node['id']] = $node;
        }

        $menu = [];
        foreach ($items as $key => $item) {
            if (isset($items[$item['parent_id']])) {
                $items[$item['parent_id']]['child'][] = &$items[$key];
            } else {
                $menu[] = &$items[$key];
            }
        }

        return $menu;
    }

    /**
     * 获取由菜单id和菜单名称组成的键值对数组
     * @return array
     */
    protected static function getMenuKeyValue()
    {
        $menuArr = self::column('menu_name', 'id');
        $menuArr[0] = '';   // 顶级菜单没有父级菜单
        return $menuArr;
    }

    /**
     * 获取分页数据
     * @param $requestParam
     * @return Menu|\think\Paginator
     */
    public static function getPaginationData($requestParam)
    {
        $paginate = self::field(['menu_url', 'menu_icon'], true)
            ->withSearch(['menu_name'], $requestParam)
            ->paginate($requestParam['limit']);

        $menuArr = self::getMenuKeyValue();
        foreach ($paginate->items() as $model) {
            $model->id = my_openssl_encrypt($model->id);
            $model->parent_name = $menuArr[$model->parent_id];
        }

        return $paginate;
    }

    /**
     * 获取被编辑的菜单对象
     * @param $id
     * @return Menu|false
     */
    public static function getEditMenu($id)
    {
        $model = self::getModelById($id, ['id', 'menu_name', 'menu_sort']);
        if ($model->isEmpty()) {
            return false;
        }

        $model->id = my_openssl_encrypt($model->id);

        return $model;
    }

    /**
     * 编辑菜单
     * @param array $requestParam
     * @return bool
     */
    public static function editMenu($requestParam)
    {
        $model = self::getModelById($requestParam['id'], ['id']);
        return $model->isEmpty() ? false : $model->save($requestParam);
    }

    public function searchMenuNameAttr($query, $value)
    {
        if (!empty($value)) {
            $query->whereLike('menu_name', '%' . $value . '%');
        }
    }
}