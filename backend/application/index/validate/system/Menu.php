<?php

namespace app\index\validate\system;

use app\common\validate\BaseValidate;

class Menu extends BaseValidate
{
    /**
     * 定义验证规则
     */
    protected $rule = [
        'id' => ['require', 'number'],
        'menu_name' => 'require',
        'menu_sort' => ['require', 'number', 'between:1,99'],
        'token_edit_menu' => ['tokenRequire', 'token:token_edit_menu'],   // 编辑菜单token
    ];

    /**
     * 定义错误信息
     */
    protected $message = [
        'id.require' => '非法参数',
        'id.number' => '非法参数',
        'menu_name.require' => '请输入菜单名称',
        'menu_sort.require' => '菜单排序，非法参数',
        'menu_sort.number' => '菜单排序，非法参数',
        'menu_sort.between' => '菜单排序范围1-99',
    ];

    /**
     * 定义验证场景
     * @var array
     */
    protected $scene = [
        'edit' => ['id'],
        'doEdit' => ['id', 'menu_name', 'menu_sort', 'token_edit_menu'],
    ];
}
