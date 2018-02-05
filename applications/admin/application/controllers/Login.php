<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
 * desc:后台登陆
 * 1034487709@qq.com
 */

class Login extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model([
            'Model_admins' => 'Madmins',
            'Model_admins_group' => 'Madmins_group',
            'Model_admins_purview' => 'Madmins_purview',
            'Model_login_log' => 'Mlogin_log',
            'Model_configes' => 'Mconfiges'
         ]);

      // $this->load->library('session');

      }

    public function index() {
        $data['domain'] = C('domain');
        $data['verify'] = $this->Mconfiges->get_one("val",array("key"=>"verify"));
        $this->load->view("login", $data);
    }

    /*
     * 验证码
     * 1034487709@qq.com
     */
    public function code(){
        $this->load->library('valicode');
        $this->valicode->outImg();
     }


    /*
     * 登录验证
     * 1034487709@qq.com
     */
    public function login(){

        if($this->input->is_ajax_request()){
            $name = $this->input->post("name",true);
            $password = $this->input->post("password",true);


            if(empty($name) || !isset($name)){
                $this->return_json(array("code"=>2,"msg"=>"用户名不能为空！"));
            }
            if(empty($password) || !isset($password)){
                $this->return_json(array("code"=>3,"msg"=>"密码不能为空！"));
            }
            session_start();

            if(!empty($name) && !empty($password))
            {
                $where['name']		= $name;
                $where['is_del']	= 1;
                $where['type']	= 0;
                #验证用户信息
                $user_info =$this->Madmins->get_one("*",$where);

                if($user_info){
                    if($user_info['disabled'] == 2){
                        $this->return_json(array("code"=>2,"msg"=>"该用户已被禁用!"));
                    }
                    if($user_info['password'] == md5($password)) {

                        $purview_ids = explode(',', $user_info['purview_ids']);
                        $user_info['purview_url'] = array_column($this->Madmins_purview->get_urls($purview_ids),"url",'id');

                        unset($user_info['password']);
                        $_SESSION['USER'] = $user_info;

                        #记录登录日志
                        $this->Mlogin_log->create(array(
                            'admin_id'		=>isset($user_info['id']) ? $user_info['id'] : 0,
                            'login_time'	=>date('Y-m-d H:i:s'),
                            'login_ip'		=>get_client_ip(),
                            'login_name'	=>$name,
                        ));
                       $this->return_json(array("code"=>0,"msg"=>"登录成功"));

                    }else{
                        $this->return_json(array("code"=>3,"msg"=>"密码错误请重新输入"));
                    }
                }else{
                    $this->return_json(array("code"=>2,"msg"=>"用户名错误"));
                }

            }
        }

    }

    /**
     * 转化为json字符串
     * @author 1034487709@qq.com
     * @param unknown $arr
     * @ruturn return_type
     */
    public function return_json($arr) {
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Allow-Headers: X-Requested-With');
        header('Content-Type: application/json');
        header('Cache-Control: no-cache');
        echo json_encode($arr);exit;
    }

    /*
     * 退出
     * 1034487709@qq.com
     */
    public function out(){
        session_start();
        session_destroy();
        tp_redirect('/login');
    }

}













