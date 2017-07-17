<?php
/**
 * @author  HID丨emotion
 * @license http://www.hiDIRECTORY_SEPARATOR.vip
 * @version 2017-3-13 0013 18:15:00
 */

namespace hidsvip\wechat;

use hidsvip\common\Cache;
use hidsvip\http\Curl;

class Wechat
{
    protected static $instance = [];
    protected static $handler  = null;
    protected        $option   = [];

    //private $accessTokenUrl = 'https://api.weixin.qq.com/cgi-bin/token';
    private $accessTokenUrl = 'http://api.ileyun.cn/weixin/index/token';

    private function __construct($option)
    {
        $this->option = $this->formatOption($option);
    }

    private function formatOption($option)
    {
        $default = [
            'appid'  => '',
            'secret' => '',
        ];

        return array_merge($default, $option);
    }

    public static function instance($option)
    {
        if (!isset(self::$instance[ $option['appid'] ])) {
            self::$instance[ $option['appid'] ] = new self($option);
        }

        return self::$instance[ $option['appid'] ];
    }

    public function getAccessToken()
    {
        $accessToken = Cache::get($this->option['appid'] . '_accessToken');
        if (empty($accessToken)) {
            $url = $this->accessTokenUrl . '?grant_type=client_credential&appid=' . $this->option['appid'] . '&secret=' . $this->option['secret'];
            $res = json_decode(Curl::get($url));
            if (isset($res->access_token)) {
                $accessToken = $res->access_token;
                Cache::set($this->option['appid'] . '_accessToken', $accessToken, intval($res->expires_in / 2));
            } else {
                throw  new \Exception(empty($res) ? 'appId 错误！' : json_encode($res));
            }
        }

        return $accessToken;
    }
}