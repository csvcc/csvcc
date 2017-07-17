<?php
/**
 * @author  HID丨emotion
 * @license http://www.hids.vip
 * @version 2017-3-27 0027 19:09:06
 */

namespace hidsvip\format;

class Xml
{
    public static function xmlToArray($xml)
    {
        if (!$xml) {
            throw exception("xml数据异常！");
        }
        //将XML转为array
        //禁止引用外部xml实体
        libxml_disable_entity_loader(true);

        return self::filterEmptyArray(json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true));
    }

    public static function arrayToXml($array)
    {
        if (!is_array($array) || count($array) <= 0) {
            throw new \Exception("数组数据异常！");
        }

        $xml = "<xml>";
        foreach ($array as $key => $val) {
            if (is_numeric($val)) {
                $xml .= "<" . $key . ">" . $val . "</" . $key . ">";
            } else {
                $xml .= "<" . $key . "><![CDATA[" . $val . "]]></" . $key . ">";
            }
        }
        $xml .= "</xml>";

        return $xml;
    }

    private static function filterEmptyArray($array)
    {
        foreach ($array as $k => $v) {
            if (is_array($v)) {
                if (count($v) > 0) {
                    $array[ $k ] = self::filterEmptyArray($v);
                } else {
                    $array[ $k ] = '';
                }
            }
        }

        return $array;
    }
}