<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
 * @author yonghau
 * desc:后台登陆
 * 254274509@qq.com
 */

class Login extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model([
            'Model_admins' => 'Madmins',
            'Model_login_log' => 'Mlogin_log'
        ]);
    }

    /*
     * 登录验证
     * 254274509@qq.com
     */
    public function index(){
        if(IS_POST){
            $name = $this->input->get_post("name", true);
            $password = $this->input->get_post("password", true);
            if(empty($name) || !isset($name)){
                $this->return_json(array("code"=>0, "msg"=>"用户名不能为空！"));
            }
            if(empty($password) || !isset($password)){
                $this->return_json(array("code"=>0, "msg"=>"密码不能为空！"));
            }
            if(!empty($name) && !empty($password))
            {
                $where['name']		= $name;
                $where['is_del']	= 1;
                #验证用户信息
                $user_info =$this->Madmins->get_one("*", $where);
                if($user_info){
                    if($user_info['disabled'] == 2){
                        $this->return_json(array("code"=>0, "msg"=>"该用户已被禁用!"));
                    }
                    if($user_info['password'] == md5($password)) {
                        unset($user_info['password']);
                        #记录登录日志
                        $this->Mlogin_log->create(array(
                            'admin_id'		=> isset($user_info['id']) ? $user_info['id'] : 0,
                            'login_time'	=> date('Y-m-d H:i:s'),
                            'login_ip'		=> get_client_ip(),
                            'login_name'	=> $name.' app登录',
                       ));
                        
                       $token = $this->setToken($user_info['id']);
                       $this->return_json(array("code" => 1, "msg"=>"登录成功", 'token' => $token, 'data' => $user_info));
                    }else{
                        $this->return_json(array("code" => 0, "msg" => "密码错误请重新输入"));
                    }
                }else{
                    $this->return_json(array("code" => 0, "msg" => "用户名错误"));
                }
            }
        }
        $this->return_json(array("code"=>0, "msg"=>"非法请求!"));
    }
}