<?php

namespace app\common\model;

use think\Model;

abstract class BaseModel extends Model
{
    /**
     * 根据指定条件获取模型对象（默认id），可指定或者排除字段
     * 例如：id = 5
     *      id = ['id', 5]
     *      id = ['id', 5, '>=']
     * @param $id
     * @param string $field
     * @param false $except
     * @return static
     */
    public static function getModelById($id, $field = '*', $except = false)
    {
        $query = static::field($field, $except);

        if (is_array($id)) {
            $field = array_shift($id);
            $condition = array_shift($id);
            $op = array_shift($id) ?? '=';
            $query->where($field, $op, $condition);
        } else {
            $query->where('id', $id);
        }

        return $query->findOrEmpty();
    }

    public function searchStartTimeAttr($query, $value)
    {
        if (!empty($value)) {
            $query->where('add_time', '>=', strtotime($value));
        }
    }

    public function searchEndTimeAttr($query, $value)
    {
        if (!empty($value)) {
            $query->where('add_time', '<=', strtotime($value));
        }
    }
}