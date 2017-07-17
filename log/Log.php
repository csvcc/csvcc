<?php
/**
 * @author  HID丨emotion
 * @license http://www.hids.vip
 * @version 2017/5/10 16:33
 */

namespace hidsvip\log;

use hidsvip\http\Request;
use hidsvip\random\Random;
use hidsvip\time\Time;

abstract class Log
{
    protected static $typeEnum = [
        // 认证
        'login'      => 1,//登陆
        'login_fail' => 4,//登陆失败
        'loginFail'  => 4,//登陆失败
        'logout'     => 9,//退出登陆
        // 系统操作
        'cron'       => 10,//计划任务
        // 调试
        'debug'      => 40,//调试输出
        'info'       => 41,//信息
        'warm'       => 42,//警告
        'error'      => 43,//错误
        // 数据修改
        'add'        => 91,//新增数据
        'save'       => 95,//修改数据
        'change'     => 95,//修改数据
        'remove'     => 99,//删除数据
        'del'        => 99,//删除数据
        'delete'     => 99,//删除数据
    ];
    protected static $instance = [];
    protected static $handler  = null;
    protected        $option   = [];

    protected function __construct($option)
    {
        $this->option = $this->formatOption($option);
    }

    public static function instance(array $options = [], $name = false)
    {
        if (empty($options) && isset(self::$handler)) {
            return self::$handler;
        }

        $type = !empty($options['type']) ? $options['type'] : 'Thinkphp5';
        if (false === $name) {
            $name = md5(serialize($options));
        }

        if (true === $name || !isset(self::$instance[ $name ])) {
            $class = false !== strpos($type, '\\') ? $type : '\\hidsvip\\log\\Log\\' . ucwords($type);

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

    public static function build($uid, $type, $module = '', $msg = '', $data = [])
    {
        $type = isset(self::$typeEnum[ $type ]) ? self::$typeEnum[ $type ] : 0;
        $data = json_encode($data);

        return [
            'id'         => Random::getId(),
            'uid'        => $uid,
            'type'       => $type,
            'module'     => $module,
            'msg'        => $msg,
            'data'       => $data,
            'gmt_create' => Time::time(),
            'ip'         => Request::getClientIp(),
        ];
    }

    abstract public function write($uid, $type, $module = '', $msg = '', $data = []);
}