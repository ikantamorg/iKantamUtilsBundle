<?php

namespace iKantam\Bundles\UtilsBundle\Utils;

use iKantam\Bundles\UtilsBundle\Utils\StringUtils as Str;

class ArrayUtils {

    /**
     * Get value from array by key
     * If value is not set - return default value
     * 
     * @param array $array
     * @param mixed(string/int) $key
     * @param mixed $default
     * @return mixed
     */
    public static function get($array, $key, $default = NULL)
    {
        return ( isset($array[$key]) ) ? $array[$key] : $default;
    }

    public static function truly($array, $key)
    {
        return isset($array[$key]) && $array[$key];
    }

    /**
     * 
     */
    public static function extract($array, $keys, $prefix = '')
    {
        $result = array();
        foreach ($keys as $key => $value) {
            $key = ((is_numeric($key)) ? $value : $key);
            $result[$key] = isset($array[$prefix.$value])
                ? $array[$prefix.$value]
                : null;
        }
        return $result;
    }

    /**
     * 
     */
    public static function omit($array, $keys, $prefix = '')
    {
        $result = array();
        foreach ($array as $key => $value) {
            $key = ((is_numeric($key)) ? $value : $key);
            if ( ! in_array($key, $keys)) {
                $result[$prefix.$key] = $value;
            }
        }
        return $result;
    }

    /**
     * 
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
     * 
     */
    public static function toEntity($array, $entity)
    {
        foreach ($array as $key => $value) {
            $key = Str::stringCamelize($key);
            $methodName = 'set'.$key;
            if (method_exists($entity, $methodName)) {
                // var_dump($methodName, $value);
                if ($value === null) {
                    // var_dump("NULL <$methodName>");
                    // $value = "NULL";
                }
                $entity->{$methodName}($value);
            } else {
                // var_dump($methodName);
            }
        }
        return $entity;
    }

    public static function fromEntity($entity, $array = array())
    {
        $result = array();
        foreach ($array as $key => $value) {
            $key = Str::stringCamelize($value);
            $methodName = 'get'.$key;
            if (method_exists($entity, $methodName)) {
                $result[$value] = $entity->{$methodName};
            } else {
                // var_dump($methodName);
            }
        }
        return $result;
    }

    public static function fromEntities($entities, $array = array()) 
    {
        $result = array();
        foreach ($entities as $entity) {
            $result[] = self::fromEntity($entity, $array);
        }
        return $result;
    }

    public static function findWhere($array, $findKey, $findValue, $default = null)
    {
        foreach ($array as $key => $value) {
            if ($value[$findKey] === $findValue) {
                return $value;
            }
        }
        return $default;
    }

    public static function pluck($array, $pluckKey, $toKey = false)
    {
        $result = array();
        foreach ($array as $key => $value) {
            if ($toKey) {
                $result[$key] = $value[$pluckKey];
            } else {
                $result[] = $value[$pluckKey];
            }
        }
        return $result;
    }

    public static function idize($array, $idKey)
    {
        $result = array();
        foreach ($array as $key => $value) {
            $result[$value[$idKey]] = $value;
        }
        return $result;
    }

    public static function group($array = array(), $groupByKey, $groupToKey, $groupIncludeKeys = array())
    {
        $result = array();
        foreach ($array as $key => $value) {
            $groupKey = $value[$groupByKey];
            if ( ! isset($result[$groupKey])) {
                $result[$groupKey] = array();
                $result[$groupKey]['id'] = $groupKey;
                $result[$groupKey][$groupToKey] = array();
                foreach ($groupIncludeKeys as $groupIncludeKey) {
                    $result[$groupKey][$groupIncludeKey] = $value[$groupIncludeKey];
                    unset($value[$groupIncludeKey]);
                }
            }
            $result[$groupKey][$groupToKey][$key] = $value;
        }
        return $result;
    }

    public static function toOrderedString($array, $order = array(), $separator = ', ')
    {
        $ordered = array();
        foreach ($order as $key => $value) {
            if (isset($array[$value])) {
                $ordered[] = $array[$value];
            }
        }
        return empty($ordered) ? '' : implode($separator, $ordered);
    }


}