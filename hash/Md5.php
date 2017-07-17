<?php
/**
 * @author  HID丨emotion
 * @license http://www.hids.vip
 * @version 2017/5/2 15:38
 */

namespace hidsvip\hash;

class Md5
{
    public static function str($str)
    {
        return md5($str);
    }

    public static function file($path)
    {
        return md5_file($path);
    }

    /**
     * @param string $path 文件真实路径
     * @param int    $size 截取字节数（默认1M）
     * @param bool   $tail 是否拼接尾部（默认false）
     *
     * @return string 文件md5散列值
     * @throws \Exception 找不到文件
     */
    public static function sub_file($path, $size = 1048576, $tail = false)
    {
        if (!file_exists($path)) {
            throw  new  \Exception('找不到文件：' . $path);
        }

        $fileSize = filesize($path);
        if ($fileSize < $size || ($tail && $fileSize < $size * 2)) {
            return self::file($path);
        }

        $fp  = fopen($path, "r");
        $str = fread($fp, $size);
        if ($tail) {
            fseek($fp, 0 - $size, SEEK_END);
            $str .= fread($fp, $size);
        }
        fclose($fp);

        return md5($str);
    }

}