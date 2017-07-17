<?php
/**
 * @author  HID丨emotion
 * @license http://www.hids.vip
 * @version 2017/6/16 15:40
 */

namespace hidsvip\wechat;

use hidsvip\common\Cache;
use hidsvip\http\Curl;

class WechatJs
{
    private $jsApiTicket = 'https://api.weixin.qq.com/cgi-bin/ticket/getticket';

    protected static $instance = [];
    protected static $handler  = null;
    protected        $option   = [];

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

    public function getJsApiTicket()
    {
        $accessToken = Wechat::instance($this->option)->getAccessToken();

        $jsApiTicket = Cache::get($this->option['appid'] . '_jsApiTicket');
        if (empty($jsApiTicket)) {
            $url = $this->jsApiTicket . '?access_token=' . $accessToken . '&type=jsapi';
            $res = json_decode(Curl::get($url));
            if (isset($res->errcode) && $res->errcode == 0) {
                $jsApiTicket = $res->ticket;
                Cache::set($this->option['appid'] . '_jsApiTicket', $jsApiTicket, intval($res->expires_in / 2));
            } else {
                throw  new \Exception(empty($res) ? 'appId 错误！' : json_encode($res));
            }
        }

        return $jsApiTicket;
    }
}