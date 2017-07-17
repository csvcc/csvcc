<?php
/**
 * @author  HID丨emotion
 * @license http://www.hids.vip
 * @version 2017-3-20 0020 11:07:46
 */

namespace hidsvip\oauth2\Oauth2;

use hidsvip\oauth2\Oauth2;

class Wechat extends Oauth2
{
    //private $authorizeUrl   = 'https://open.weixin.qq.com/connect/oauth2/authorize';
    private $authorizeUrl    = 'http://api.ileyun.cn/weixin/oauth2/authorize';
    private $accessTokenUrl  = 'https://api.weixin.qq.com/sns/oauth2/access_token';
    private $userInfoUrl     = 'https://api.weixin.qq.com/sns/userinfo';
    private $userInfoUrlBase = 'https://api.weixin.qq.com/cgi-bin/user/info';

    public function __construct($option)
    {
        parent::__construct($option);
        if (!isset($_SESSION)) {
            session_start();
        }
    }

    protected function formatOption($option)
    {
        $default = [
            'appid'         => '',
            'secret'        => '',
            'redirect_uri'  => $this->getSelfUrl(),
            'response_type' => 'code',
            'scope'         => 'snsapi_base',
            'state'         => '',
        ];

        return array_merge($default, $option);
    }

    private function getAuthorizeUrl()
    {
        //Oauth 标准参数
        $params = array(
            'appid'         => $this->option['appid'],
            'redirect_uri'  => $this->option['redirect_uri'],
            'response_type' => $this->option['response_type'],
            'scope'         => $this->option['scope'],
            'state'         => '',
        );

        return $this->authorizeUrl . '?' . http_build_query($params) . '#wechat_redirect';
    }

    private function getAccessTokenUrl()
    {
        $params = array(
            'appid'      => $this->option['appid'],
            'secret'     => $this->option['secret'],
            'code'       => $_GET['code'],
            'grant_type' => 'authorization_code',
        );

        return $this->accessTokenUrl . '?' . http_build_query($params);
    }

    private function getUserInfoUrl($oauthToken, $openId)
    {
        $params = [
            'access_token' => $oauthToken,
            'openid'       => $openId,
            'lang'         => 'zh_CN',
        ];

        switch ($this->option['scope']) {
            case 'snsapi_userinfo':
                return $this->userInfoUrl . '?' . http_build_query($params);
                break;
            // case 'snsapi_base':
            //     return $this->userInfoUrlBase . '?' . http_build_query($params);
            //     break;
            default:
                $params['access_token'] = \hidsvip\wechat\Wechat::instance($this->option)->getAccessToken();

                return $this->userInfoUrlBase . '?' . http_build_query($params);
        }
    }

    public function getUserInfo($refresh = false)
    {
        $cacheName = 'userInfo';
        $userInfo  = isset($_SESSION[ $cacheName ]) ? $_SESSION[ $cacheName ] : [];
        if ($refresh || empty($userInfo)) {
            if (isset($_GET['code'])) {
                if (isset($userInfo['code']) && $userInfo['code'] == $_GET['code']) {
                    return $userInfo;
                }
                $accessToken            = $this->getAccessToken();
                $res                    = $this->httpGet($this->getUserInfoUrl($accessToken['access_token'], $accessToken['openid']));
                $userInfo               = $this->formatUserInfo(json_decode($res, true));
                $userInfo['code']       = $_GET['code'];
                $_SESSION[ $cacheName ] = $userInfo;
            } else {
                header('Location:' . $this->getAuthorizeUrl());
                exit;
            }
        }

        return $userInfo;
    }

    public function getOpenId()
    {
        $userInfo = $this->getUserInfo();

        return $userInfo['openid'];
    }

    protected function getAccessToken()
    {
        $res = $this->httpGet($this->getAccessTokenUrl());
        if (!empty($res)) {
            $res = json_decode($res, true);
            if (isset($res['openid'])) {
                $_SESSION['openid'] = $res['openid'];

                return $res;
            } else {
                throw new \Exception('获取 Access Token 出错：' . json_encode($res));
            }
        } else {
            throw  new \Exception('获取用户信息失败！');
        }
    }

    protected function formatUserInfo($userInfo = [])
    {
        /** @var array $convert 需要转换的字段名 */
        $convert = [

        ];

        foreach ($convert as $k => $v) {
            if (isset($userInfo[ $v ])) {
                $userInfo[ $k ] = $userInfo[ $v ];
                unset($userInfo[ $v ]);
            } else {
                $userInfo[ $k ] = '';
            }
        }

        return $userInfo;
    }

    private function httpGet($url)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 500);
        // 为保证第三方服务器与微信服务器之间数据传输的安全性，所有微信接口采用https方式调用，必须使用下面2行代码打开ssl安全校验。
        // 如果在部署过程中代码在此处验证失败，请到 http://curl.haxx.se/ca/cacert.pem 下载新的证书判别文件。
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_URL, $url);

        $res = curl_exec($curl);
        curl_close($curl);

        return $res;
    }
}