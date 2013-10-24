<?php
/**
 * User: Dred
 * Date: 28.05.13
 * Time: 17:58
 */

namespace iKantam\UtilsBundle\Utils;


class StringUtils
{

    private static $password_allow_chars = 'abcefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890@#%$*';

    /**
     * This class should not be instantiated
     */
    private function __construct()
    {

    }


    /**
     * Generate random password
     *
     * @param int $length
     *
     * @return string
     */
    public static function generatePassword($length = 12)
    {
        $pwd = str_shuffle(self::$password_allow_chars);

        return substr($pwd, 0, $length);
    }

    /**
     * Replace tokens in given string
     *
     * @param $text
     * @param array $replacement
     * @param string $token_symbol
     * @param string $wrap_side
     *
     * @return mixed
     */
    public static function tokensReplace($text, array $replacement, $token_symbol = '%', $wrap_side = 'both')
    {
        $wrap_side = strtolower($wrap_side);

        switch($wrap_side){
            case 'left':
                $callback = function ($key) use ($token_symbol) {
                    return $token_symbol.$key;
                };
                break;
            case 'right':
                $callback = function ($key) use ($token_symbol) {
                    return $key.$token_symbol;
                };
                break;
            default:
                $callback = function ($key) use ($token_symbol) {
                    return $token_symbol.$key.$token_symbol;
                };
                break;
        }

        foreach ($replacement as $key => $replace) {
            $search = $callback($key);
            $text = str_replace($search, $replace, $text);
        }

        return $text;

    }

    /**
     * Repeat Symfony original function not to have dependencies on it,
     * to use this function in another util classes.
     * 
     * Camelizes a string.
     *
     * @param string $id A string to camelize
     * @return string The camelized string
     */
    public static function camelize($id)
    {
        return preg_replace_callback(
            '/(^|_|\.)+(.)/', 
            function ($match) { 
                return ('.' === $match[1] ? '_' : '').strtoupper($match[2]); 
            }, 
            $id
        );
    }

    /**
     * Repeat Symfony original function not to have dependencies on it,
     * to use this function in another util classes
     * 
     * A string to underscore.
     *
     * @param string $id The string to underscore
     * @return string The underscored string
     */
    public static function underscore($id)
    {
        return strtolower(preg_replace(
            array('/([A-Z]+)([A-Z][a-z])/', '/([a-z\d])([A-Z])/'), 
            array('\\1_\\2', '\\1_\\2'), 
            strtr($id, '_', '.')
        ));
    }

}