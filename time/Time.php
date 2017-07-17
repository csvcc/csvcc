<?php
/**
 * @author  HID丨emotion
 * @license http://www.hids.vip
 * @version 2017-3-13 0013 9:49:07
 */

namespace hidsvip\time;

class Time
{
    /**
     * @param string $format       时间格式
     * @param int    $gm_timestamp 格林威治时间戳
     * @param int    $timezone     时区
     *
     * @return string 时间字符串
     */
    public static function date($format = 'Y-m-d H:i:s', $gm_timestamp = null, $timezone = null)
    {
        if (is_null($gm_timestamp)) {
            $gm_timestamp = self::time();
        }
        if (is_null($timezone)) {
            $timezone = date('Z');
        } else {
            $timezone = $timezone * 3600;
        }

        return date($format, $gm_timestamp + $timezone);
    }

    /**
     * @return int 格林威治时间戳
     */
    public static function time()
    {
        return time() - date('Z');
    }

    /**
     * @param string $time 时间字符串
     *
     * @return false|int 时间字符串对应格林威治时间戳
     */
    public static function strtotime($time)
    {
        return strtotime($time) - date('Z');
    }
}