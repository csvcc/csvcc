<?php
/**
 * @author  HID丨emotion
 * @license http://www.hids.vip
 * @version 2017/4/24 10:52
 */

namespace hidsvip\http;

use hidsvip\log\Log;
use hidsvip\time\Time;

class Ajax
{

    //get请求方式
    const METHOD_GET = 'GET';
    //post请求方式
    const METHOD_POST = 'POST';

    public static function get($url)
    {
        self::exec($url);
    }

    public static function post($url, $params)
    {
        self::exec($url, self::METHOD_POST, $params);
    }

    /**
     * 发起http异步请求
     *
     * @param string $url            http地址
     * @param string $method         请求方式
     * @param array  $params         参数
     * @param string $ip             支持host配置
     * @param int    $connectTimeout 连接超时，单位为秒
     *
     * @throws \Exception
     */
    private static function exec($url, $method = self::METHOD_GET, $params = array(), $ip = null, $connectTimeout = 1)
    {

        $urlInfo = parse_url($url);

        $host = $urlInfo['host'];
        $port = isset($urlInfo['port']) ? $urlInfo['port'] : 80;
        $path = isset($urlInfo['path']) ? $urlInfo['path'] : '/';
        !$ip && $ip = $host;

        $method = strtoupper(trim($method)) !== self::METHOD_POST ? self::METHOD_GET : self::METHOD_POST;
        $params = http_build_query($params);

        if ($method === self::METHOD_GET && strlen($params) > 0) {
            $path .= '?' . $params;
        }

        $fp = fsockopen($ip, $port, $errorCode, $errorInfo, $connectTimeout);

        if ($fp === false) {
            throw new \Exception('Connect failed , error code: ' . $errorCode . ', error info: ' . $errorInfo);
        } else {
            $http = "$method $path HTTP/1.1\r\n";
            $http .= "Host: $host\r\n";
            // $http .= "Content-type: application/x-www-form-urlencoded\r\n";
            $http .= "Connection:Close\r\n";
            $method === self::METHOD_POST && $http .= "Content-Length: " . strlen($params) . "\r\n";
            $method === self::METHOD_POST && $http .= $params . "\r\n\r\n";

            fwrite($fp, $http);;
            // echo !feof($fp) && fgets($fp, 2);

            // while (!feof($fp)) {
            //     echo fgets($fp, 128);
            // }

            if (fwrite($fp, $http) === false) {
                throw new \Exception('Request failed.');
            } else {
                //Time::time();
            }
            // fclose($fp);
        }

        return true;
    }
}