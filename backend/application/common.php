<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件

/**
 * 定义AES加密和解密方法
 */
define('AES_METHOD', 'aes-256-ecb');

/**
 * 定义AES加密和解码的key
 */
define('AES_KEY', 'ks3TRS@4pXOgZXd8');

if (!function_exists('my_openssl_encrypt')) {
    /**
     * 获取url可使用的base64编码后的字符串
     * @param $data
     * @return string|string[]
     */
    function my_openssl_encrypt($data)
    {
        $data = openssl_encrypt($data, AES_METHOD, AES_KEY);
        return str_replace(['+', '/', '='], ['-', '_', ''], $data);
    }
}

if (!function_exists('my_openssl_decrypt')) {
    /**
     * 解密
     * @param $data
     * @return false|string
     */
    function my_openssl_decrypt($data)
    {
        $data = str_replace(['-', '_'], ['+', '/'], $data);

        $mod4 = strlen($data) % 4;
        if ($mod4) {
            $data .= substr('====', $mod4);
        }

        return openssl_decrypt($data, AES_METHOD, AES_KEY);
    }
}

if (!function_exists('safe_division')) {
    /**
     * 安全除法运算（防止除0）
     * @param $divisor
     * @param $dividend
     * @param int $decimals
     * @return float|int
     */
    function safe_division($divisor, $dividend, $decimals = 2)
    {
        return $divisor == 0 || $dividend == 0 ? 0 : round($divisor / $dividend, $decimals);
    }
}

if (!function_exists('percentage_calc')) {
    /**
     * 百分比计算
     * @param $divisor
     * @param $dividend
     * @param bool $needSymbol
     * @param int $decimals
     * @return string
     */
    function percentage_calc($divisor, $dividend, $needSymbol = true, $decimals = 4)
    {
        $result = safe_division($divisor, $dividend, $decimals) * 100;
        if ($needSymbol) {
            $result .= '%';
        }

        return $result;
    }
}

if (!function_exists('combination')) {
    /**
     * 组合
     * @param array $array 给定数组
     * @param int $m 组合个数
     * @param int $startIndex 搜索起始位置
     * @param array $path 节点值
     * @param array $result 结果
     */
    function combination($array, $m, $startIndex, &$path, &$result)
    {
        // 中止条件
        if (count($path) == $m) {
            $result[] = $path; // 节点值存入结果
            return;
        }
        // 遍历
//        $n = count($array);
        $n = count($array) - ($m - count($path)) + 1;   // 剪枝
        for ($i = $startIndex; $i < $n; $i++) {
            $path[] = $array[$i];
            combination($array, $m, $i + 1, $path, $result);
            // 回溯
            array_pop($path);
        }
    }
}

if (!function_exists('full_combination')) {
    /**
     * 求给定数组的元素的全组合
     * @param $array
     * @return array
     */
    function full_combination($array)
    {
        $result = [];
        for ($i = 1; $i <= count($array); $i++) {
            $path = [];
            $perResult = [];
            combination($array, $i, 0, $path, $perResult);
            // 降维
            foreach ($perResult as $value) {
                $result[] = $value;
            }
        }

        return $result;
    }
}

if (!function_exists('explode_semicolon')) {
    /**
     * 分号explode
     * @param $string
     * @return false|string[]
     */
    function explode_semicolon($string)
    {
        return explode(';', $string);
    }
}

if (!function_exists('implode_semicolon')) {
    /**
     * 分号implode
     * @param $pieces
     * @return string
     */
    function implode_semicolon($pieces)
    {
        return implode(';', $pieces);
    }
}

if (!function_exists('explode_comma')) {
    /**
     * 逗号explode
     * @param $string
     * @return false|string[]
     */
    function explode_comma($string)
    {
        return explode(',', $string);
    }
}

if (!function_exists('implode_comma')) {
    /**
     * 逗号implode
     * @param $pieces
     * @return string
     */
    function implode_comma($pieces)
    {
        return implode(',', $pieces);
    }
}

if (!function_exists('get_value')) {
    /**
     * 获取指定key的value
     * @param $array
     * @param $key
     * @return mixed|string
     */
    function get_value($array, $key)
    {
        return isset($array[$key]) ? $array[$key] : '';
    }
}