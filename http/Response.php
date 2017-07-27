<?php
/**
 * @author  HID丨emotion
 * @license http://www.hids.vip
 * @version 2017/7/27 13:36
 */

namespace hidsvip\http;

class Response
{
    public static function download($file, $filename = null)
    {
        if (is_null($filename)) {
            $filename = basename($file);
        }

        header("Content-type: application/octet-stream");

        //处理中文文件名
        $ua = $_SERVER["HTTP_USER_AGENT"];
        if (preg_match("/MSIE/", $ua)) {
            header('Content-Disposition: attachment; filename="' . rawurlencode($filename) . '"');
        } elseif (preg_match("/Firefox/", $ua)) {
            header("Content-Disposition: attachment; filename*=\"utf8''" . $filename . '"');
        } else {
            header('Content-Disposition: attachment; filename="' . $filename . '"');
        }

        //让Xsendfile发送文件
        header("X-Sendfile: $file");
    }
}