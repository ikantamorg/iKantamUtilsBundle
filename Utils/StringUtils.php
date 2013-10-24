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

}