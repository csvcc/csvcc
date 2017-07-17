<?php
/**
 * @author  HID丨emotion
 * @license http://www.hids.vip
 * @version 2017-3-27 0027 19:14:08
 */

namespace hidsvip\http;

class Curl
{
    private static $curls = [];
    private static $curl  = null;

    public static function postXml($url, $xml)
    {
        self::initCurl($url);

        curl_setopt(self::$curl, CURLOPT_POST, true);
        curl_setopt(self::$curl, CURLOPT_POSTFIELDS, $xml);

        return curl_exec(self::$curl);
    }

    public static function postJson($url, $json)
    {
        self::initCurl($url);
        if (is_array($json) || is_object($json)) {
            $json = json_encode($json);
        }

        curl_setopt(self::$curl, CURLOPT_POST, true);
        curl_setopt(self::$curl, CURLOPT_POSTFIELDS, $json);
        curl_setopt(self::$curl, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($json),
        ));

        return curl_exec(self::$curl);
    }

    public static function get($url)
    {
        self::initCurl($url);

        return curl_exec(self::$curl);
    }

    private static function initCurl($url)
    {
        $name = md5($url);

        if (!isset(self::$curls[ $name ]) || is_null(self::$curls[ $name ])) {
            self::$curl = curl_init();
            curl_setopt(self::$curl, CURLOPT_URL, $url);
            curl_setopt(self::$curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt(self::$curl, CURLOPT_TIMEOUT, 500);
            self::$curls[ $name ] = self::$curl;
            self::changeProtocol();
        } else {
            self::$curl = self::$curls[ $name ];
        }
    }

    private static function changeProtocol($isHttps = null)
    {
        if (is_null($isHttps)) {
            $isHttps = self::checkHttps();
        }

        if ($isHttps) {
            curl_setopt(self::$curl, CURLOPT_SSL_VERIFYPEER, true);
            curl_setopt(self::$curl, CURLOPT_SSL_VERIFYHOST, 2);
            curl_setopt(self::$curl, CURLOPT_CAINFO, dirname(__FILE__) . DIRECTORY_SEPARATOR . 'Curl' . DIRECTORY_SEPARATOR . 'caCert.pem');
        } else {
            curl_setopt(self::$curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt(self::$curl, CURLOPT_SSL_VERIFYHOST, false);
        }
    }

    private static function checkHttps($url = '')
    {
        //TODO 获取是否https
        if (empty($url) || strlen($url) <= 5) {
            return (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443);
        }

        return (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443);
    }
}