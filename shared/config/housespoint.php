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
		'1' => '露骨画面',
		'2' => '药物保健品',
		'3' => '低俗医疗广告',
		'4' => '贷款',
		'5' => '房地产类广告',
	),
		
	//点位状态
	"points_status"=>array(
		'1'=>"有空闲",
		'3'=>"已占满"
	),

)
;
