<?php
/**
 * @author  HID丨emotion
 * @license http://www.hids.vip
 * @version 2017-3-13 0013 16:04:27
 */

namespace hidsvip\common;

class Cache
{
    public static function set($name, $value, $expire = 0)
    {
        $filename = self::getCachePath() . self::getCacheKey($name);
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
        $filename = self::getCachePath() . self::getCacheKey($name);
        if (!is_file($filename)) {
            return $default;
        }
        $content = file_get_contents($filename);
        if (false !== $content) {
            $expire = (int)substr($content, 8, 12);
            if (0 != $expire && $_SERVER['REQUEST_TIME'] > filemtime($filename) + $expire) {
                //缓存过期删除缓存文件
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

    private static function getCachePath()
    {
        $dir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'hidsvip' . DIRECTORY_SEPARATOR;
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        return $dir;
    }

    private static function getCacheKey($name)
    {
        return md5($name);
    }

    private static function unlink($path)
    {
        return is_file($path) && unlink($path);
    }
}