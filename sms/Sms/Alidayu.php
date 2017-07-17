<?php
/**
 * @author  HID丨emotion
 * @license http://www.hids.vip
 * @version 2017/5/9 9:09
 */

namespace hidsvip\sms\Sms;

use hidsvip\sms\Sms;

class Alidayu extends Sms
{
    private $topSdkPath;
    private $topClient;

    protected function __construct($option)
    {
        parent::__construct($option);
        $this->topSdkPath = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'Alidayu' . DIRECTORY_SEPARATOR;
        include_once $this->topSdkPath . 'TopSdk.php';
        $this->topClient            = new \TopClient();
        $this->topClient->appkey    = $this->option['appid'];
        $this->topClient->secretKey = $this->option['secret'];
        $this->topClient->format    = 'json';
    }

    protected function formatOption($option)
    {
        $default = [
            'appid'     => '',
            'secret'    => '',
            'sign_name' => '大鱼测试',
        ];

        return array_merge($default, $option);
    }

    public function tmplSend($recNum, $tmplCode, $param = [], $signName = '')
    {
        $signName = empty($signName) ? $this->option['sign_name'] : $signName;

        include_once $this->topSdkPath . 'top' . DIRECTORY_SEPARATOR . 'request' . DIRECTORY_SEPARATOR . 'AlibabaAliqinFcSmsNumSendRequest.php';
        $req = new \AlibabaAliqinFcSmsNumSendRequest;
        $req->setSmsType("normal");
        $req->setSmsFreeSignName($signName);
        $req->setSmsParam(json_encode($param));
        $req->setRecNum($recNum);
        $req->setSmsTemplateCode($tmplCode);
        $resp = $this->topClient->execute($req);

        if (isset($resp->result) && isset($resp->result->success) && $resp->result->success === true) {
            return true;
        } else {
            throw  new \Exception('阿里大于短信模板发送失败：' . json_encode($resp));
            //return false;
        }
    }
}