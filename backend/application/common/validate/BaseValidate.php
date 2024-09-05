<?php

namespace app\common\validate;

use think\Validate;
use think\facade\Lang;
use think\Container;

class BaseValidate extends Validate
{
    /**
     * 正则定义
     * @var string[]
     */
    protected $regex = [
        'nameRegex' => '/^[\x{4e00}-\x{9fa5}A-Za-z0-9_]{2,16}+$/u',
        'usernameRegex' => '[a-zA-Z0-9_]{2,16}',
        'passwordRegex' => '[a-zA-Z0-9_]{8,16}',
        'uidRegex' => '/^[1-9]\d{8}$/',
    ];

    /**
     * token验证提示信息
     * @var string[]
     */
    protected $tokenMessage = [
        'tokenRequire' => '令牌数据无效请刷新页面重试~',
        'token' => '令牌数据无效请刷新页面重试~',
    ];

    /**
     * 支持tokenRequire的验证
     * @access protected
     * @param  string    $field  字段名
     * @param  mixed     $value  字段值
     * @param  mixed     $rules  验证规则
     * @param  array     $data  数据
     * @param  string    $title  字段描述
     * @param  array     $msg  提示信息
     * @return mixed
     */
    protected function checkItem($field, $value, $rules, $data, $title = '', $msg = [])
    {
        if (isset($this->remove[$field]) && true === $this->remove[$field] && empty($this->append[$field])) {
            // 字段已经移除 无需验证
            return true;
        }

        // 支持多规则验证 require|in:a,b,c|... 或者 ['require','in'=>'a,b,c',...]
        if (is_string($rules)) {
            $rules = explode('|', $rules);
        }

        if (isset($this->append[$field])) {
            // 追加额外的验证规则
            $rules = array_unique(array_merge($rules, $this->append[$field]), SORT_REGULAR);
            unset($this->append[$field]);
        }

        $i      = 0;
        $result = true;

        foreach ($rules as $key => $rule) {
            if ($rule instanceof \Closure) {
                $result = call_user_func_array($rule, [$value, $data]);
                $info   = is_numeric($key) ? '' : $key;
            } else {
                // 判断验证类型
                list($type, $rule, $info) = $this->getValidateType($key, $rule);

                if (isset($this->append[$field]) && in_array($info, $this->append[$field])) {

                } elseif (array_key_exists($field, $this->remove) && (null === $this->remove[$field] || in_array($info, $this->remove[$field]))) {
                    // 规则已经移除
                    $i++;
                    continue;
                }

                // 验证类型
                // 添加tokenRequire验证
                if (isset(self::$type[$type])) {
                    $result = call_user_func_array(self::$type[$type], [$value, $rule, $data, $field, $title]);
                } elseif ('must' == $info || 0 === strpos($info, 'require') || 0 === strpos($info, 'tokenRequire') || (!is_null($value) && '' !== $value)) {
                    // 验证数据
                    $result = call_user_func_array([$this, $type], [$value, $rule, $data, $field, $title]);
                } else {
                    $result = true;
                }
            }

            if (false === $result) {
                // 验证失败 返回错误信息
                if (!empty($msg[$i])) {
                    $message = $msg[$i];
                    if (is_string($message) && strpos($message, '{%') === 0) {
                        $message = Lang::get(substr($message, 2, -1));
                    }
                } else {
                    $message = $this->getRuleMsg($field, $title, $info, $rule);
                }

                return $message;
            } elseif (true !== $result) {
                // 返回自定义错误信息
                if (is_string($result) && false !== strpos($result, ':')) {
                    $result = str_replace(':attribute', $title, $result);

                    if (strpos($result, ':rule') && is_scalar($rule)) {
                        $result = str_replace(':rule', (string) $rule, $result);
                    }
                }

                return $result;
            }
            $i++;
        }

        return $result;
    }

    /**
     * 支持获取token验证提示信息
     * @access protected
     * @param string $attribute 字段英文名
     * @param string $title 字段描述名
     * @param string $type 验证规则名称
     * @param mixed $rule 验证规则数据
     * @return string
     */
    protected function getRuleMsg($attribute, $title, $type, $rule)
    {
        $lang = Container::get('lang');

        if (isset($this->message[$attribute . '.' . $type])) {
            $msg = $this->message[$attribute . '.' . $type];
        } elseif (isset($this->tokenMessage[$type])) {
            // token验证提示信息
            $msg = $this->tokenMessage[$type];
        } elseif (isset($this->message[$attribute][$type])) {
            $msg = $this->message[$attribute][$type];
        } elseif (isset($this->message[$attribute])) {
            $msg = $this->message[$attribute];
        } elseif (isset(self::$typeMsg[$type])) {
            $msg = self::$typeMsg[$type];
        } elseif (0 === strpos($type, 'require')) {
            $msg = self::$typeMsg['require'];
        } else {
            $msg = $title . $lang->get('not conform to the rules');
        }

        if (!is_string($msg)) {
            return $msg;
        }

        if (0 === strpos($msg, '{%')) {
            $msg = $lang->get(substr($msg, 2, -1));
        } elseif ($lang->has($msg)) {
            $msg = $lang->get($msg);
        }

        if (is_scalar($rule) && false !== strpos($msg, ':')) {
            // 变量替换
            if (is_string($rule) && strpos($rule, ',')) {
                $array = array_pad(explode(',', $rule), 3, '');
            } else {
                $array = array_pad([], 3, '');
            }
            $msg = str_replace(
                [':attribute', ':1', ':2', ':3'],
                [$title, $array[0], $array[1], $array[2]],
                $msg);
            if (strpos($msg, ':rule')) {
                $msg = str_replace(':rule', (string)$rule, $msg);
            }
        }

        return $msg;
    }

    /**
     * token require
     * @param $value
     * @return bool
     */
    protected function tokenRequire($value)
    {
        return !empty($value) || '0' == $value;
    }
}