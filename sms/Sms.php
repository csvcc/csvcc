<?php
/**
 * @author  HID丨emotion
 * @license http://www.hids.vip
 * @version 2017-3-13 0013 16:00:08
 */

namespace hidsvip\sms;

class Sms
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

        $type = !empty($options['type']) ? $options['type'] : 'Alidayu';
        if (false === $name) {
            $name = md5(serialize($options));
        }

        if (true === $name || !isset(self::$instance[ $name ])) {
            $class = false !== strpos($type, '\\') ? $type : '\\hidsvip\\sms\\Sms\\' . ucwords(strtolower($type));

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

    public function tmplSend($recNum, $tmplCode, $param = [], $signName = '')
    {

    }

    public function contentSend($recNum, $content, $signName = '')
    {

    }
}