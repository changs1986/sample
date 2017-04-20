<?php
/**
 * Data validator 
 *
 * User: chanceJaw
 * Date: 16/5/30 上午10:59
 */

namespace NN\Logic;

class ValidateLogic
{
    /**
     * 验证手机号码
     *
     * @param $phone
     * @return bool
     */
    public static function validPhoneNum($phone)
    {
        if (strlen($phone) != 11 || !preg_match('/^1[34578][0-9]{9}$/', $phone))
        {
            return false;
        }

        return true;
    }

    public static function validNumber($num)
    {
        if (trim($num) != '' && is_numeric($num) && 0 <= $num) {
            return true;
        }
        return false;
    }

    public static function validVar($key, $arr)
    {
        if (empty($key) && empty($arr)) {
            return false;
        }
        return isset($arr[$key]) && trim($arr[$key]) != '' ? true : false;
    }
}
