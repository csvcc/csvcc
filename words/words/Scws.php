<?php
/**
 * @author  HID丨emotion
 * @license http://www.hids.vip
 * @version 2017/7/17 14:23
 */

namespace hidsvip\words\words;

use hidsvip\common\Cache;

class Scws
{
    public function split($keyword, $isTop = false)
    {
        $cacheName = 'hidsvip\words' . $keyword . ($isTop ? '1' : '2');
        $words     = Cache::get($cacheName);
        if (empty($words)) {
            require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'scws' . DIRECTORY_SEPARATOR . 'pscws4.class.php';
            $pscws = new \PSCWS4('utf8');
            $pscws->set_charset('utf-8');
            $pscws->set_dict(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'scws' . DIRECTORY_SEPARATOR . 'dict.utf8.xdb');
            $pscws->set_ignore(true);

            $pscws->send_text($keyword);

            if ($isTop) {
                $res = $pscws->get_tops();
            } else {
                $res = [];
                while ($some = $pscws->get_result()) {
                    $res = array_merge($res, $some);
                }
            }

            $words = [];
            foreach ($res as $v) {
                $words[] = $v['word'];
            }
            Cache::set($cacheName, $words);
        }

        return $words;
    }
}