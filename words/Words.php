<?php
/**
 * @author  HID丨emotion
 * @license http://www.hids.vip
 * @version 2017/7/17 15:31
 */

namespace hidsvip\words;

class Words
{
    protected static $instance = [];
    protected static $handler  = null;
    protected        $option   = [];

    private function __construct($option)
    {
        $this->option = $this->formatOption($option);
    }

    public static function instance(array $options = [], $name = false)
    {
        if (empty($options) && isset(self::$handler)) {
            return self::$handler;
        }

        $type = !empty($options['type']) ? $options['type'] : 'Scws';
        if (false === $name) {
            $name = md5(serialize($options));
        }

        if (true === $name || !isset(self::$instance[ $name ])) {
            $class = false !== strpos($type, '\\') ? $type : '\\hidsvip\\words\\words\\' . ucwords($type);

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