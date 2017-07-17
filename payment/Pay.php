<?php
/**
 * @author  HID丨emotion
 * @license http://www.hids.vip
 * @version 2017-2-17 0017 10:50:12
 */

namespace hidsvip\payment;

abstract class Pay
{
    protected static $instance = [];
    protected static $handler  = null;
    protected        $option   = [];

    public function __construct($option)
    {
        $this->option = $this->formatOption($option);
    }

    public static function instance(array $options = [], $name = false)
    {
        if (empty($options) && isset(self::$handler)) {
            return self::$handler;
        }

        $type = isset($options['type']) && !empty($options['type']) ? $options['type'] : 'Wepay';
        if (false === $name) {
            $name = md5(serialize($options));
        }

        if (true === $name || !isset(self::$instance[ $name ])) {
            $class = false !== strpos($type, '\\') ? $type : '\\hidsvip\\payment\\Pay\\' . ucwords($type);

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
}