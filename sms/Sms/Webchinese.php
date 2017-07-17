<?php
/**
 * @author  HID丨emotion
 * @license http://www.hids.vip
 * @version 2017/5/26 17:04
 */

namespace hidsvip\sms\Sms;

use hidsvip\http\Curl;
use hidsvip\sms\Sms;

class Webchinese extends Sms
{
    protected function formatOption($option)
    {
        $default = [
            'appid'  => '',
            'secret' => '',
        ];

        return array_merge($default, $option);
    }

    public function contentSend($recNum, $content, $signName = '')
    {
        $param = [
            'Uid'     => $this->option['appid'],
            'Key'     => $this->option['secret'],
            'smsMob'  => $recNum,
            'smsText' => $content . $this->formatSignName($signName),
        ];
        $url   = 'http://utf8.sms.webchinese.cn/?' . http_build_query($param);

        $res = Curl::get($url);

        if ($res === '1') {
            return true;
        } else {
            switch ($res) {
                case '-1':
                    $errMsg = '没有该用户账户';
                    break;
                case '-2':
                    $errMsg = '接口密钥不正确 [查看密钥]，不是账户登陆密码';
                    break;
                case '-21':
                    $errMsg = 'MD5接口密钥加密不正确';
                    break;
                case '-3':
                    $errMsg = '短信数量不足';
                    break;
                case '-11':
                    $errMsg = '该用户被禁用';
                    break;
                case '-14':
                    $errMsg = '短信内容出现非法字符';
                    break;
                case '-4':
                    $errMsg = '手机号格式不正确';
                    break;
                case '-41':
                    $errMsg = '手机号码为空';
                    break;
                case '-42':
                    $errMsg = '短信内容为空';
                    break;
                case '-51':
                    $errMsg = '短信签名格式不正确，接口签名格式为：【签名内容】';
                    break;
                case '-6':
                    $errMsg = 'IP限制';
                    break;
                default:
                    $errMsg = '未知错误';
            }
            throw new \Exception($errMsg);
        }
    }

    private function formatSignName($signName = '')
    {
        if (empty($signName)) {
            return '';
        } else {
            return '【' . $signName . '】';
        }
    }
}