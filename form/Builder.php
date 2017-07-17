<?php
/**
 * @author  HID丨emotion
 * @license http://www.hids.vip
 * @version 2017-3-8 0008 15:28:28
 */

namespace hidsvip\form;

abstract class Builder
{
    protected static $handler;
    protected static $instance = [];
    protected        $option   = [
        'type' => 'H',
    ];

    private function __construct($option)
    {
        $this->option = $option;
    }

    public static function instance(array $options = [], $name = false)
    {
        if (empty($options) && isset(self::$handler)) {
            return self::$handler;
        }

        $type = !empty($options['type']) ? $options['type'] : 'H';
        if (false === $name) {
            $name = md5(serialize($options));
        }

        if (true === $name || !isset(self::$instance[ $name ])) {
            $class = false !== strpos($type, '\\') ? $type : '\\hidsvip\\form\\Builder\\' . ucwords($type);

            if (true === $name) {
                return new $class($options);
            } else {
                self::$instance[ $name ] = new $class($options);
            }
        }
        self::$handler = self::$instance[ $name ];

        return self::$handler;
    }

    abstract public function text($name, $title, $value = '', $option = []);

    abstract public function hidden($name, $title, $value = '', $option = []);

    abstract public function password($name, $title, $value = '', $option = []);

    abstract public function date($name, $title, $value = '', $option = []);

    abstract public function radio($name, $title, $enum, $value = '', $option = []);

    abstract public function checkbox($name, $title, $enum, $value = [], $option = []);

    abstract public function submit($action, $option = []);

    abstract public function reset($option = []);

    abstract public function file($name, $title, $value = '', $option = []);

    abstract public function files($name, $title, $value = [], $option = []);

    abstract public function image($name, $title, $value = '', $option = []);

    abstract public function images($name, $title, $value = [], $option = []);

    abstract public function video($name, $title, $value = '', $option = []);

    abstract public function textarea($name, $title, $value = '', $option = []);

    abstract public function editor($name, $title, $value = '', $option = []);

    abstract public function select($name, $title, $enum, $value = '', $option = []);

    abstract public function area($name, $title, $value = '', $option = []);
}