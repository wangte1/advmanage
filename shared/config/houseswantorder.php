<?php

if(! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 社区订单配置文件
 */
$config = array(
 
	//社区订单派单状态
	'houses_want_status' => array(
		'1' => '业务主管审核中',
	    '2' => '审核通过',
		'3' => '已转预定订单',
	    '4' => '审核不通过'
	),
)
;
