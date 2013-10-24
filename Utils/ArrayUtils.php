<?php

namespace iKantam\UtilsBundle\Utils;

use iKantam\UtilsBundle\Utils\StringUtils as Str;


class ArrayUtils {

    /**
     * Retrieve a single value from array.
     * If the key does not exist in the array, the default value will be returned instead.
     * 
     * @param array $array
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function get($array, $key, $default = NULL)
    {
        return (isset($array[$key])) ? $array[$key] : $default;
    }

    /**
     * Determine whether the array element is set and is not falsy
     * Discover what is falsy {@link http://php.net/manual/en/language.types.boolean.php}
     * 
     * @param array $array
     * @param string $key
     * @return bool
     */
    public static function truly($array, $key)
    {
        return isset($array[$key]) && $array[$key];
    }

    /**
     * Determine whether the array element is set and is falsy
     * Discover what is falsy {@link http://php.net/manual/en/language.types.boolean.php}
     * 
     * @param array $array
     * @param string $array
     * @return bool
     */
    public static function falsy($array, $key)
    {
        return isset($array[$key]) && ! $array[$key];
    }

    /**
     * Add prefix to each key in array
     * 
     * @param array $array
     * @param string $prefix
     * @return array
     */
    public static function prefix($array, $prefix)
    {
        $result = array();
        foreach ($array as $key => $value) {
            $result[$prefix.$key] = $value;
        }
        return $result;
    }

    /**
     * Remove prefix from each key in array
     * 
     * @param array $array
     * @param string $prefix
     * @return array
     */
    public static function unprefix($array, $prefix)
    {
        $result = array();
        foreach ($array as $key => $value) {
            $result[substr($key, strlen($prefix))] = $value;
        }
        return $result;
    }

    /**
     * When array element is an array itself
     * Copy the value from element, to key, pointing at this element
     * 
     * Use:
     *      $array = array(
     *          array('id' => 10, 'name' => 'John'),
     *          array('id' => 30, 'name' => 'Alice'),
     *      );
     *      ArrayUtils::idize($array, 'id');
     *      // ---------------------
     *      array(2) { 
     *          [10]=> array(2) { 
     *              ["id"]=> int(10) ["name"]=> string(4) "John" 
     *          } 
     *          [30]=> array(2) { 
     *              ["id"]=> int(30) ["name"]=> string(5) "Alice" 
     *          } 
     *      }
     *      // ---------------------
     * 
     * @param array $array
     * @param string $id
     * @param bool $copy - if set to false, delete copied value from element
     */
    public static function idize($array, $id, $copy = true)
    {
        $result = array();
        foreach ($array as $key => $value) {
            $result[$value[$id]] = $value;
            if ( ! $copy) {
                unset($result[$value[$id]][$id]);
            }
        }
        return $result;
    }

    /**
     * Extract only those elements from array for which the keys are passed
     * Append prefix to each key of the resulting array
     * 
     * Use: 
     *      $array = array('a' => 1, 'c_x' => 5, 'c_z' => 4, 'd' => 7, 'c_y' => 9);
     *      ArrayUtils::extract($array, array('x', 'y' => 'g'), 'c_'));
     *      // ---------------------
     *      array(2) { ["x"]=> int(5) ["g"]=> int(9) }
     *      // ---------------------
     *
     * @param array $array
     * @param array $keys - May have values and key-value pairs. 
     * If key-value pair found, key from a pair - key from initial array,
     * value from a pair - key in a resulting array. 
     * Used to replace original keys by the new ones.
     * @param string $prefix - append prefix, to extract elements that have keys 
     * with common prefix
     * @return array
     */
    public static function extract($array, $keys, $prefix = '')
    {
        $result = array();
        foreach ($keys as $key => $value) {
            if (is_numeric($key)) {
                $key = $value;
            }
            if (isset($array[$prefix . $key])) {
                $result[$value] = $array[$prefix . $key];
            }
        }
        return $result;
    }

    /**
     * Do not extract elements for which the keys are passed. Opposite to "extract"
     * 
     * 
     * Use:
     *      $array = array('a' => 1, 'x' => 5, 'z' => 4, 'd' => 7, 'y' => 9);
     *      ArrayUtils::omit($array, array('x', 'y));
     *      // ---------------------
     *      array(3) { ["a"]=> int(1) ["z"]=> int(4) ["d"]=> int(7) }
     *      // ---------------------
     * 
     * @param array $array
     * @param array $keys
     * @return array
     */
    public static function omit($array, $keys)
    {
        $result = array();
        foreach ($array as $key => $value) {
            if ( ! in_array($key, $keys)) {
                $result[$key] = $value;
            }
        }
        return $result;
    }

    /**
     * Get array of the elements' values by key
     * 
     * Use:
     *      $array = array(
     *          array('id' => 10, 'name' => 'John'),
     *          array('id' => 30, 'name' => 'Alice'),
     *      );
     *      ArrayUtils::pluck($array, 'id');
     *      // ---------------------
     *      array(2) { [0]=> int(10) [1]=> int(30) }
     *      // ---------------------
     * 
     * @param array $array
     * @param string $pluckKey
     * @param bool $saveKey
     * @return array
     */
    public static function pluck($array, $pluckKey, $saveKey = false)
    {
        $result = array();
        foreach ($array as $key => $value) {
            if ($saveKey) {
                $result[$key] = $value[$pluckKey];
            } else {
                $result[] = $value[$pluckKey];
            }
        }
        return $result;
    }

    /**
     * Return first array element, where value of the element by key is equal to passed value
     * 
     * Use:
     *      $array = array(
     *          array('id' => 10, 'name' => 'John'),
     *          array('id' => 30, 'name' => 'Alice'),
     *       );
     *      ArrayUtils::find($array, 'id', 10);
     *      // ---------------------
     *      array(2) { ["id"]=> int(10) ["name"]=> string(4) "John" }
     *      // ---------------------
     * 
     * @param array $array
     * @param string $findKey
     * @param mixed $findValue
     * @param mixed $default
     * @return mixed
     */
    public static function find($array, $findKey, $findValue, $default = null)
    {
        foreach ($array as $key => $value) {
            if ($value[$findKey] == $findValue) {
                return $value;
            }
        }
        return $default;
    }

    /**
     * Order array by a set of values
     * 
     * Use:
     *      $array = array('a' => 1, 'x' => 5, 'z' => 4, 'd' => 7, 'y' => 9);
     *      $order = array('x','y','z');
     *      ArrayUtils::order($array, $order, true);
     *      // ---------------------
     *      array(5) { [0]=> int(5) [1]=> int(9) [2]=> int(4) [3]=> int(1) [4]=> int(7) }
     *      // ---------------------
     * 
     * @param array $array
     * @param array $order
     * @param bool $all - if set to true, values, that are not in $order,
     * will be appended to the end of thre resulting array
     * @return array
     */
    public static function order($array, $order = array(), $all = false)
    {
        $ordered = array();
        foreach ($order as $key => $value) {
            if (isset($array[$value])) {
                $ordered[] = $array[$value];
            }
        }
        if ($all) {
            $ordered = array_merge($ordered, array_keys(array_diff(array_flip($array), $order)));
        }
        return $ordered;
    }

    /**
     * Fill an entity with values from array by the same keys
     * Entity must have setters for keys, ie for
     *      array('name' => 'John')
     * entity must have method "setName($value)", and so on.
     * 
     * @param array $array
     * @param mixed $entity
     * @return mixed
     */
    public static function toEntity($entity, $array)
    {
        foreach ($array as $key => $value) {
            $methodName = 'set' . Str::camelize($key);
            if (method_exists($entity, $methodName)) {
                call_user_method_array($methodName, $entity, array($value));
            }
        }
        return $entity;
    }

    /**
     * Get entity values by keys passed in array
     * Entity must have getters for keys, ie for
     *      array('name', 'age')
     * entity muse have methods "getName()" and "getAge()", and so on.
     * 
     * @param mixed $entity
     * @param array $keys
     * @return array
     */
    public static function fromEntity($entity, $keys = array())
    {
        $result = array();
        foreach ($keys as $key) {
            $methodName = 'get' . Str::camelize($key);
            if (method_exists($entity, $methodName)) {
                $result[$key] = call_user_method($methodName, $entity);
            }
        }
        return $result;
    }

}