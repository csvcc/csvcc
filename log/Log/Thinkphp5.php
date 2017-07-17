<?php
/**
 * @author  HID丨emotion
 * @license http://www.hids.vip
 * @version 2017/5/10 17:01
 */

namespace hidsvip\log\Log;

use hidsvip\log\Log;
use think\Db;

class Thinkphp5 extends Log
{
    public function write($uid, $type, $module = '', $msg = '', $data = [])
    {
        $data = self::build($uid, $type, $module, $msg, $data);
        $res  = Db::name('log')->insert($data);

        return is_numeric($res);
    }

    public function truncate()
    {

    }
}