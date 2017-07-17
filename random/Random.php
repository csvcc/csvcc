<?php
/**
 * @author  HID丨emotion
 * @license http://www.hids.vip
 * @version 2017-3-10 0010 16:36:49
 */

namespace hidsvip\random;

class Random
{
    public static function getString($length = 16)
    {
        $chars        = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $chars_length = strlen($chars) - 1;
        $str          = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, $chars_length), 1);
        }

        return $str;
    }

    public static function getNumberString($length = 8)
    {
        $str = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= rand(0, 9);
        }

        return $str;
    }

    public static function getNumber($min = 0, $max = 9)
    {
        return rand($min, $max);
    }

    public static function getId()
    {
        return uniqid('', true);
    }

    public static function getIp()
    {
        $ip_long  = array(
            array('607649792', '608174079'), //36.56.0.0-36.63.255.255
            array('975044608', '977272831'), //58.30.0.0-58.63.255.255
            array('999751680', '999784447'), //59.151.0.0-59.151.127.255
            array('1019346944', '1019478015'), //60.194.0.0-60.195.255.255
            array('1038614528', '1039007743'), //61.232.0.0-61.237.255.255
            array('1783627776', '1784676351'), //106.80.0.0-106.95.255.255
            array('1947009024', '1947074559'), //116.13.0.0-116.13.255.255
            array('1987051520', '1988034559'), //118.112.0.0-118.126.255.255
            array('2035023872', '2035154943'), //121.76.0.0-121.77.255.255
            array('2078801920', '2079064063'), //123.232.0.0-123.235.255.255
            array('-1950089216', '-1948778497'), //139.196.0.0-139.215.255.255
            array('-1425539072', '-1425014785'), //171.8.0.0-171.15.255.255
            array('-1236271104', '-1235419137'), //182.80.0.0-182.92.255.255
            array('-770113536', '-768606209'), //210.25.0.0-210.47.255.255
            array('-569376768', '-564133889'), //222.16.0.0-222.95.255.255
        );
        $rand_key = mt_rand(0, 14);

        return long2ip(mt_rand($ip_long[ $rand_key ][0], $ip_long[ $rand_key ][1]));
    }
}