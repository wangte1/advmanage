<?php

if(! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 社区订单配置文件
 */
$config = array(
    //订单状态
    'houses_order_status' => array(
        'code' => array(
        	'upload_pic' => 1,
            'to_sign' => 2,
            'to_make' => 3,
            'in_make' => 4,
            'to_install' => 5,
            'in_install' => 6,
            'to_inspect' => 7,
            'in_put' => 8,
            'uninstall' => 9,
        	'takeout' => 10
        ),
        'text' => array( //灯箱和户外高杆
        	'1' => '待上传广告画面',
            '2' => '联系单待签字',
            '3' => '待制作',
            '4' => '制作中',
            '5' => '制作完成（待上画派单）',
            '6' => '派单完成',
            '7' => '上画完成（待验收）',
            '8' => '投放中',
            '9' => '待下画（下画派单中）',
        	'10' => '已下画',
        ),
    ),
		
	//社区资源订单类型
	'houses_order_type' => array(
		'1' => '冷光灯箱',
		'2' => '广告机',
	),
		
	//社区资源订单类型
	'houses_assign_type' => array(
		'1' => '上画派单',
		'2' => '下画派单',
	),
		
	//社区订单派单状态
	'houses_assign_status' => array(
		'1' => '待派单',
		'2' => '已派单（未确认）',
		'3' => '已派单（已确认）',
		'4' => '已上画（待审核）',
		'5' => '已上画（审核通过）',
		'6' => '已上画（审核未通过）',
		'7' => '已下画（待审核）',
		'8' => '已下画（审核通过）',
		'9' => '已下画（审核未通过）',
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
