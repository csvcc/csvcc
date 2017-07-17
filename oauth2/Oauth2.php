<?php
/**
 * @author  HID丨emotion
 * @license http://www.hids.vip
 * @version 2017-3-20 0020 11:06:21
 */

namespace hidsvip\oauth2;

abstract class Oauth2
{
    protected static $instance = [];
    protected static $handler  = null;
    protected        $option   = [];

    protected function __construct($option)
    {
        $this->option = $this->formatOption($option);
    }

    public static function instance(array $options = [], $name = false)
    {
        if (empty($options) && isset(self::$handler)) {
            return self::$handler;
        }

        $type = !empty($options['type']) ? $options['type'] : 'Wechat';
        if (false === $name) {
            $name = md5(serialize($options));
        }

        if (true === $name || !isset(self::$instance[ $name ])) {
            $class = false !== strpos($type, '\\') ? $type : '\\hidsvip\\oauth2\\Oauth2\\' . ucwords($type);

            if (true === $name) {
                return new $class($options);
            } else {
                self::$instance[ $name ] = new $class($options);
            }
        }
        self::$handler = self::$instance[ $name ];

        return self::$handler;
    }

    protected function formatOption($option)
    {
        return $option;
    }

    protected function getSelfUrl()
    {
        return ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://") . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    }

    abstract public function getOpenId();
    abstract public function getUserInfo();
}