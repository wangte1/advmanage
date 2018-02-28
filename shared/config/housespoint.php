<?php

if(! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 社区点位配置文件
 */
$config = array(
     //点位位置
    'point_addr' => array(
        '1' => '门禁',
        '2' => '地面电梯前室',
    	'3' => '地下电梯前室',
    ),
		
	//禁投放行业
	'put_trade' => array(
		'1' => '房地产',
		'2' => '美容',
		'3' => '其他',
	),
		
	//点位状态
	"points_status"=>array(
		'1'=>"有空闲",
		'3'=>"已占满"
	),

)
;
