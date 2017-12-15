<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 管理员类型
 */
$config = array(
    "log_type"=>array(
        "1"=>"添加",
        "2"=>"编辑",
        "3"=>"删除",
    ),
    //媒体类型
    "media_type"=>array(
        '1'=>"公交灯箱",
        '2'=>"户外高杆",
        '3'=>"机场LED",
        '4'=>"火车站LED",
    ),
    //站台表现形式
    "media_express_form"=>array(
        '1'=>"灯箱广告",
        '2'=>"高杆广告",
        '3'=>"LED广告",
    ),
		
	//楼盘类型
	"houses_type"=>array(
		'1'=>"topGrade",	//高级楼盘
		'2'=>"middleGrade",	//中级楼盘
		'3'=>"lowGrade",	//一般楼盘
	),
		
	//社区客户类型
	"houses_customer_type"=>array(
		'1'=>"地产",
		'2'=>"医疗",
		'3'=>"农业",
		'4'=>"运输",
		'5'=>"美容",
	),

    //客户类型
    "customer_type"=>array(
        '1'=>"地产",
        '2'=>"非地产",
    ),
    //点位状态
    "points_status"=>array(
        '1'=>"空闲",
        '3'=>"占用"
    ),
    //订单年份
    'years'=>array(
        "2015",
        "2016",
        "2017",
        "2018",
        "2019",
        "2020",
        "2021",
        "2022",
    ),

     //百度地图Key
    "baidu_key"=>"hOvXSvpLgqY5vkXdOnrM2Zup"

);