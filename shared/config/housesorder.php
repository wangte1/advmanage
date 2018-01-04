<?php

if(! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 社区订单配置文件
 */
$config = array(
    //订单状态
    'houses_order_status' => array(
        'code' => array(
            'to_sign' => 1,
            'to_make' => 2,
            'in_make' => 3,
            'to_install' => 4,
            'in_install' => 5,
            'to_inspect' => 6,
            'in_put' => 7,
            'uninstall' => 8
        ),
        'text' => array( //灯箱和户外高杆
            '1' => '联系单待签字',
            '2' => '待制作',
            '3' => '制作中',
            '4' => '制作完成（待派单）',
            '5' => '派单完成',
            '6' => '上画完成（待验收）',
            '7' => '投放中',
            '8' => '已下画'
        ),
    ),
		
	//社区资源订单类型
	'houses_order_type' => array(
		'1' => '冷光灯箱',
		'2' => '广告机',
	),
		
	//社区订单派单状态
	'houses_assign_status' => array(
		'1' => '待派单',
		'2' => '已派单（未确认）',
		'3' => '已派单（已确认）',
		'4' => '已上画（待审核）',
		'5' => '已上画（已审核）'
	),
		
    //广告性质
    'adv_nature' => array('电商配送', '购买', '包销','公司广告','公益广告','置换'),

    //广告频次
    'adv_frequency' => array('5秒/次', '10秒/次','240次/天 10秒/次', '480次/天 10秒/次'),

    //小样颜色
    'sample_color' => array('浅色', '原色', '深色'),

    //委托内容
    'leave_content' => array(
        '1' => '仅制作',
        '2' => '制作及安装'
    ),

    //安装类型
    'install_type' => array(
        '1' => '覆盖',
        '2' => '替换原画'
    ),
    
)
;
