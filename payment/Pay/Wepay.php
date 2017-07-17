<?php
/**
 * @author  HID丨emotion
 * @license http://www.hids.vip
 * @version 2017-3-27 0027 14:31:20
 */

namespace hidsvip\payment\Pay;

use hidsvip\format\Xml;
use hidsvip\http\Curl;
use hidsvip\oauth2\Oauth2;
use hidsvip\payment\Pay;

class Wepay extends Pay
{
    public function formatOption($option)
    {
        $default = [
            'appid'  => '',
            'mch_id' => '',
            'key'    => '',
        ];

        return array_merge($default, $option);
    }

    public function Pay($order)
    {
        $signDate = [
            'appId'     => $this->option['appid'],
            'timeStamp' => time(),
            'nonceStr'  => $this->createNonceStr(32),
            'package'   => 'prepay_id=' . $this->unifiedOrder($order)['prepay_id'],
            'signType'  => 'MD5',
        ];
        ksort($signDate);

        $WXPay = [
            'appId'     => $signDate['appId'],
            'timestamp' => $signDate['timeStamp'],
            'nonceStr'  => $signDate['nonceStr'],
            'package'   => $signDate['package'],
            'signType'  => $signDate['signType'],
            //'paySign'   => strtoupper(md5($this->ToUrlParams($signDate))),
            'paySign'   => $this->makeSign($signDate),
        ];

        return $WXPay;
    }

    public function unifiedOrder($order)
    {
        if (!isset($order['order_sn'])) {
            throw new \Exception('商户订单号错误！');
        }

        $payData = [
            'appid'            => $this->option['appid'],
            'mch_id'           => $this->option['mch_id'],
            'nonce_str'        => $this->createNonceStr(32),
            'body'             => $order['goods_name'],
            'out_trade_no'     => $order['order_sn'],
            'total_fee'        => $order['price'] * 100,
            'spbill_create_ip' => input('server.REMOTE_ADDR'),
            'notify_url'       => URL('Notify/wechat', '', '', input('server.SERVER_NAME')),
            'trade_type'       => 'JSAPI',
            'openid'           => Oauth2::instance()->getOpenId(),
        ];

        ksort($payData);
        $payData['sign'] = $this->makeSign($payData);

        $xml       = Xml::arrayToXml($payData);
        $res       = Curl::postXml('https://api.mch.weixin.qq.com/pay/unifiedorder', $xml);
        $res_array = Xml::xmlToArray($res);

        return $res_array;
    }

    private function getSign()
    {

    }

    private function makeSign($data)
    {
        ksort($data);

        return strtoupper(md5($this->ToUrlParams($data) . '&key=' . $this->option['key']));
    }

    private function ToUrlParams($data)
    {
        $buff = "";
        foreach ($data as $k => $v) {
            if ($k != "sign" && $v != "" && !is_array($v)) {
                $buff .= $k . "=" . $v . "&";
            }
        }

        $buff = trim($buff, "&");

        return $buff;
    }

    private function createNonceStr($length = 16)
    {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $str   = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }

        return $str;
    }

    public function notify()
    {
        $content = file_get_contents("php://input");
        //$content = '<xml><appid><![CDATA[wxbfc5fe09ba259d28]]></appid><bank_type><![CDATA[CMB_CREDIT]]></bank_type><cash_fee><![CDATA[1]]></cash_fee><fee_type><![CDATA[CNY]]></fee_type><is_subscribe><![CDATA[Y]]></is_subscribe><mch_id><![CDATA[1269359401]]></mch_id><nonce_str><![CDATA[jurrKS8RxmW5QxMvcXbwwQlUqgXRu7f2]]></nonce_str><openid><![CDATA[oZdDDt3cQLnr8xmUTMtl8dyljnlc]]></openid><out_trade_no><![CDATA[2017032818483241464644]]></out_trade_no><result_code><![CDATA[SUCCESS]]></result_code><return_code><![CDATA[SUCCESS]]></return_code><sign><![CDATA[66DA5BF793787210DD65231D81EFCFB1]]></sign><time_end><![CDATA[20170328184838]]></time_end><total_fee>1</total_fee><trade_type><![CDATA[JSAPI]]></trade_type><transaction_id><![CDATA[4000432001201703284974467845]]></transaction_id></xml>';

        $param = Xml::xmlToArray($content);

        $signData         = [
            'appid'          => $this->option['appid'],
            'mch_id'         => $this->option['mch_id'],
            'transaction_id' => $param['transaction_id'],
            'nonce_str'      => $this->createNonceStr(32),
        ];
        $signData['sign'] = $this->makeSign($signData);

        $res     = Curl::postXml('https://api.mch.weixin.qq.com/pay/orderquery', Xml::arrayToXml($signData));
        $res_arr = Xml::xmlToArray($res);

        if ($res_arr['trade_state'] == 'SUCCESS') {
            $order = [
                'order_sn'   => $res_arr['out_trade_no'],
                'price'      => $res_arr['total_fee'] / 100,
                'pay_status' => 99,
            ];

            echo 'success';

            return $order;
        } else {
            return false;
        }
    }
}