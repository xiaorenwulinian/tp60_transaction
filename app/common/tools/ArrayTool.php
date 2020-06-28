<?php

namespace app\common\tools;


class ArrayTool
{

    /**
     * 获取所有的树形菜单
     * @param $data
     * @param int $parent_id
     * @param int $level
     * @param bool $isClear
     * @return array
     */
    public static function getTree($data, $parent_id = 0, $level = 0, $isClear = TRUE)
    {
        static $ret = [];
        if ($isClear) {
            $ret = [];
        }
        foreach ($data as $k => $v) {
            if($v['parent_id'] == $parent_id) {
                $v['level'] = $level;
                $ret[] = $v;
                self::getTree($data, $v['id'], $level+1, FALSE);
            }
        }
        return $ret;
    }

    /**
     * 获取子集id
     * @param $data
     * @param int $parent_id
     * @param bool $isClear
     * @return array
     */
    public static function getChildren($data, $parent_id = 0, $isClear = TRUE)
    {
        static $ret = [];
        if ($isClear) {
            $ret = [];
        }
        foreach ($data as $k => $v) {
            if($v['parent_id'] == $parent_id) {
                $ret[] = $v['id'];
                self::getChildren($data, $v['id'], FALSE);
            }
        }
        return $ret;
    }


    /**
     * 多维数组排序
     * @param $array
     * @param int $sortDirection  1:positive,2:reverse
     * @return array
     */
    public static function sortRecursive($array, $sortDirection = 1)
    {
        foreach ($array as &$value) {
            if (is_array($value)) {
                $value = static::sortRecursive($value, $sortDirection);
            }
        }

        if ($sortDirection === 1) {
            if (static::isAssociateArray($array)) {
                ksort($array);
            } else {
                sort($array);
            }
        } else {
            if (static::isAssociateArray($array)) {
                krsort($array);
            } else {
                rsort($array);
            }
        }

        return $array;
    }

    /**
     * 判断一个数组是否是关联数组（key 非连续以0开始的数字）
     * @param $array
     * @return bool
     */
    public static function isAssociateArray($array)
    {
        $keys = array_keys($array);
        return $keys !== array_keys($keys);
    }


    /**
     * 数组转换成类似 get 请求参数
     * @param $array
     * @return string
     */
    public static function transferQuery($array)
    {
        return http_build_query($array, null, '&', PHP_QUERY_RFC3986);
    }

    /**
     * 将数据转换成数组格式
     * @param $value
     * @return array
     */
    public static function warp($value)
    {
        if (is_null($value)) {
            return [];
        }
        return is_array($value) ? $value : [$value];
    }

    /**
     * 打乱数组
     * @param $array
     * @param null $seed
     * @return mixed
     */
    public static function shuffle($array, $seed = null)
    {
        if (is_null($seed)) {
            shuffle($array);
        } else {
            mt_srand($seed);
            shuffle($seed);
            mt_srand();
        }
        return $array;
    }

    public  static function  random($array, $number = null)
    {
        $requested = is_null($number) ? 1 : $number;

        $count = count($array);

        if ($requested > $count) {
            throw new \Exception("请求参数错误");
        }

        if (is_null($number)) {
            return $array[array_rand($array)];
        }

        if ((int)$number === 0) {
            return [];
        }

        $keys = array_rand($array, $number);

        $ret = [];

        foreach ((array)$keys as $key) {
            $ret[] = $array[$key];
        }

        return $ret;
    }


    public static function exists($array, $key)
    {
        if ($array instanceof \ArrayAccess) {
            return $array->offsetExists($key);
        }
        return array_key_exists($key, $array);
    }


    /**
     * Cross join the given arrays, returning all possible permutations.
     *
     * @param array ...$arrays
     * @return array
     */
    public static function crossJoin(...$arrays)
    {
        $results = [[]];

        foreach ($arrays as $index => $array) {
            $append = [];

            foreach ($results as $product) {
                foreach ($array as $item) {
                    $product[$index] = $item;

                    $append[] = $product;
                }
            }

            $results = $append;
        }

        return $results;
    }

    /**
     * Divide an array into two arrays. One with keys and the other with values.
     *
     * @param array $array
     * @return array
     */
    public static function divide($array)
    {
        return [array_keys($array), array_values($array)];
    }

    /**
     * Return the first element in an array passing a given truth test.
     *
     * @param array         $array
     * @param callable|null $callback
     * @param mixed         $default
     * @return mixed
     */
    public static function first($array, callable $callback = null, $default = null)
    {
        if (is_null($callback)) {
            if (empty($array)) {
                return $default instanceof \Closure ? $default() : $default;
//                return value($default);
            }

            foreach ($array as $item) {
                return $item;
            }
        }

        foreach ($array as $key => $value) {
            if (call_user_func($callback, $value, $key)) {
                return $value;
            }
        }
        return $default instanceof \Closure ? $default() : $default;
//        return value($default);
    }

    /**
     * Return the last element in an array passing a given truth test.
     *
     * @param array         $array
     * @param callable|null $callback
     * @param mixed         $default
     * @return mixed
     */
    public static function last($array, callable $callback = null, $default = null)
    {
        if (is_null($callback)) {
            if (empty($array)) {
                return $default instanceof \Closure ? $default() : $default;
            } else {
                return end($array);
            }
        }

        return static::first(array_reverse($array, true), $callback, $default);
    }

}
