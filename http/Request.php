<?php
/**
 * @author  HID丨emotion
 * @license http://www.hids.vip
 * @version 2017/5/12 9:29
 */

namespace hidsvip\http;

class Request
{
    private static $clientIp = null;

    public static function getClientIp()
    {
        if (!is_null(self::$clientIp)) {
            return self::$clientIp;
        }
        $ips = [];
        $ip  = null;
        if (!empty($_SERVER["HTTP_CLIENT_IP"])) {
            $ip = $_SERVER["HTTP_CLIENT_IP"];
        }
        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) { //获取代理ip
            $ips = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        }
        if ($ip) {
            $ips = array_unshift($ips, $ip);
        }

        $count = count($ips);
        for ($i = 0; $i < $count; $i++) {
            if (!preg_match("/^(10|172\.16|192\.168)\./i", $ips[ $i ])) {//排除局域网ip
                $ip = $ips[ $i ];
                break;
            }
        }
        $tip = empty($_SERVER['REMOTE_ADDR']) ? $ip : $_SERVER['REMOTE_ADDR'];
        if ($tip == "127.0.0.1") { //获得本地真实IP
            $tip = self::getServerIp();
        }
        self::$clientIp = $tip;

        return $tip;
    }

    public static function getServerIp()
    {
        return gethostbyname($_SERVER['SERVER_NAME']);
    }

    public static function getSelfUrl()
    {
        return ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://") . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    }
}