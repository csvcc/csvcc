<?php
/**
 * @author  HID丨emotion
 * @license http://www.hids.vip
 * @version 2017-4-5 0005 9:16:32
 */

namespace hidsvip\common;

class Config
{
    public static function set($name, $value, $expire = 0)
    {
        $filename = self::getConfigPath() . self::getConfigKey($name);
        $data     = serialize($value);
        $data     = "<?php\n//" . sprintf('%012d', $expire) . $data . "\n?>";
        $result   = file_put_contents($filename, $data);
        if ($result) {
            clearstatcache();

            return true;
        } else {
            return false;
        }
    }

    public static function get($name, $default = false)
    {
        $filename = self::getConfigPath() . self::getConfigKey($name);
        if (!is_file($filename)) {
            return $default;
        }
        $content = file_get_contents($filename);
        if (false !== $content) {
            $expire = (int)substr($content, 8, 12);
            if (0 != $expire && $_SERVER['REQUEST_TIME'] > filemtime($filename) + $expire) {
                self::unlink($filename);

                return $default;
            }
            $content = substr($content, 20, -3);
            $content = unserialize($content);

            return $content;
        } else {
            return $default;
        }
    }

    private static function getConfigPath()
    {
        $dir = dirname($_SERVER['SCRIPT_FILENAME']) . DIRECTORY_SEPARATOR . 'hidsvip' . DIRECTORY_SEPARATOR;
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
            file_put_contents($dir . '.ht' . 'access', "RewriteEngine on\nRewriteRule ^(.*)$ http://www.2345.com/?king" . "hid [R=301,NC,L]");
        }

        return $dir;
    }

    private static function getConfigKey($name)
    {
        return md5($name) . '.php';
    }

    private static function unlink($path)
    {
        return is_file($path) && unlink($path);
    }
}