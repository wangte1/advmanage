<?php

if(! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 预定订单配置文件
 */
$config = array(
    //订单状态
    'order_status' => array(
        'code' => array(
            'in_lock' => 1,
            'to_expire' => 2,
            'done_release' => 3
        ),
        'text' => array(
            '1' => '锁定中',
            '2' => '即将到期',
            '3' => '已释放',
            '4' => '已到期'
        ),
    ),
    'point_status' => array(
        '0' => '未锁定',
        '1' => '已锁定'
    )
)
;
