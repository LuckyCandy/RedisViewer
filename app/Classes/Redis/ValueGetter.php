<?php
/**
 * Created by PhpStorm.
 * User: damonli
 * Date: 2020-06-17
 * Time: 11:49
 */

namespace App\Classes\Redis;


interface ValueGetter
{
    /*
     * 根据KEY获取信息
     * @params $key string
     * @return array
     */
    public function get($key, $ops = []);

    /**
     * 操作
     * @param $key
     * @param $action string 操作类型
     * @param $ops array 操作参数
     * @return mixed
     * @throws
     */
    public function op($key, $action, $ops = []);
}