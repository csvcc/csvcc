<?php
/**
 * @author  HID丨emotion
 * @license http://www.hids.vip
 * @version 2017-2-16 0016 10:12:05
 */

namespace hidsvip\http;

abstract class Uploader
{
    protected static $instance = [];
    protected        $option   = [];
    protected        $files    = null;

    public function __construct($option)
    {
        $this->option = $this->formatOption($option);
        $this->files  = $_FILES;
    }

    public static function instance($type = 'Local', $option = [])
    {
        if (!isset(self::$instance[ $type ])) {
            $class = '\\hidsvip\\http\\Uploader\\' . $type;
            if (class_exists($class)) {
                self::$instance[ $type ] = new $class($option);
            } else {
                throw new \Exception('class not exists:' . $class);
            }
        }

        return self::$instance[ $type ];
    }

    protected function formatOption($option)
    {
        return $option;
    }

    abstract public function upload();
}