<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 短信发送接口配置文件
 * 
 */
$config = array(
	
    'sms_config' => array(
            'sn'    => 'SDK-WSS-010-08943',         //序列号
            'pwd'   => '7460Bdef',                  //密码
            'request_url'   => 'sdk.entinfo.cn',    //请求地址
            'request_port'  => 8061,
            
            'return_url'    => 'http://utf8.sms.webchinese.cn/?Uid=ziyouzhuwu&Key=82a42e39abd7406e09bb&smsMob=',   // 发送后获取结果地址
            'time_out'      => 10,          //超时时间
            
            'waring'        => '，1分钟内有效，请您尽快输入！（温馨提醒：为了您的账户安全，请勿告知他人）',
            'company_symbol'=> '【惠民安居】',    //公司标识符
            
            'nvalidation_time' => 3600,      //验证码失效时间 一个小时后
    ),
                
    'sms_config_huaxing' => array(
            'sn'    => '101100-WEB-HUAX-670180',    //注册码
            'pwd'   => 'FRYAKODA',                  //密码
            'request_url'   => 'http://www.stongnet.com/sdkhttp/sendsms.aspx',          //即时请求地址
            'request_timing_url'  => 'http://www.stongnet.com/sdkhttp/sendschsms.aspx', //定时短信请求地址
            'waring'        => '，1分钟内有效，请您尽快输入！（温馨提醒：为了您的账户安全，请勿告知他人）',
            'company_symbol'=> '【惠民安居】',    //公司标识符
            'nvalidation_time' => 3600,    //验证码失效时间 一个小时后
            'repeat_send_time' => 60,      //验证码发送间隔不小于60秒
    ),
    
);
