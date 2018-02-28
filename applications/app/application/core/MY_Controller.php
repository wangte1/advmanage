<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

    public $data = array();
    public $pageconfig = array();
    public $isPost = false;
    public $isGet = false;

    public function __construct() {

        parent::__construct();
        $this->load->model([
            'Model_token' => 'Mtoken',
        ]);
        $_GET = xss_clean($_GET);
        $_POST = xss_clean($_POST);

        $this->data['domain'] = C('domain');
        $this->data['action'] = $this->uri->segment(1);

    }
    
    public function setToken($user_id){
        $data['user_id'] = $user_id;
        $data['token'] = encrypt(['user_id' => $user_id, 'expires' => time()+(7*3600*24)]);
        $res  = $this->Mtoken->count(['user_id' => $user_id]);
        if($res){
            $this->Mtoken->update_info(['token' => $data['token']], ['user_id' => $user_id]);
        }else{
            $this->Mtoken->create($data);
        }
        return $data['token'];
    }
    
    public function getToken($user_id){
        $info = $this->Mtoken->get_one('token', ['user_id' => $user_id]);
        if(!$info){
            return null;
        }
        return $info['token'];
    }

    /*
     * 操作日志
     * @param $operate_id 操作人ID
     * @param $operate_type 操作类型 1-添加 2-编辑 3-删除
     * @param $operate_content 操作内容
     */
    public function write_log($operate_id,$operate_type,$operate_content){
        $this->load->model(['Model_admins_operate_log' => 'Madmins_operate_log']);
        $post_data['operate_id'] = $operate_id;
        $post_data['operate_type'] = $operate_type;
        $post_data['operate_content'] = $operate_content;
        $post_data['create_time'] = time();
        $this->Madmins_operate_log->create($post_data);

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

    /**
     * 请求成功返回
     * @author 1034487709@qq.com
     * @param unknown $data
     * @param string $msg
     * @ruturn return_type
     */
    public function return_success($data = array(),$msg = 'request is ok') {

        $this->return_json(
            array(
                'status'=> 0,
                'data'    => $data,
                'msg'   => $msg,
            )
        );

    }

    /**
     * 请求失败返回
     * @author 1034487709@qq.com
     * @param string $result
     * @param string $success_msg
     * @param string $failure_msg
     * @ruturn return_type
     */
    public function return_failed ( $msg = 'request failed',$data = '',$status = -1) {

        $this->return_json(
            array(
                'status'    => isset($status) ? $status : 1,
                'msg'       => $msg,
                'data'        => $data
            )
        );
    }

    /**
     * 创建接口请求URL
     * @author 1034487709@qq.com
     * @param string $path
     * @return string
     */
    public function create_url($path = ''){
        return sprintf('%s/%s',$this->data['domain']['service']['url'],$path);
    }

    /**
     * 接口日志记录（此方法只限于接口监控使用）
     * @author 1034487709@qq.com
     * @param unknown $data
     * @ruturn return_type
     */
    private function log_message($url = '', $data = array()){

        //日志初始化参数
        $params = array(
            'path'=>C('log.api.path'),
            'level'=>C('log.api.level')
        );

        //日志开关
        if(C('log.api.enable') === false){
            return ;
        }

        //加载日志工具
        $this->load->library('Logfile',$params);

        //接口时差，单位为毫秒
        $cost_time = $this->benchmark->elapsed_time('start','end') * 1000;

        if(isset($data['status']))
        {
            if($data['status'] == C('status.success.value'))
            {
                //返回成功，记录info日志
                $return_data = 'success';
                $message = sprintf('%s | %s | %s | %s',$data['status'],$cost_time,$url,$return_data);
                $this->logfile->info($message);
            }
            else
            {
                //返回错误，记录error日志
                $return_data = json_encode($data);
                $message = sprintf('%s | %s | %s | %s',$data['status'],$cost_time,$url,$return_data);
                $this->logfile->error($message);
            }
        }
        else
        {
            //格式错误，或者http请求未到达，记录error日志
            $return_data = 'http request error';
            $message = sprintf('%s | %s | %s | %s',$data['status'],$cost_time,$url,$return_data);
            $this->logfile->error($message);
        }

        
        $this->data['domain'] = C('domain');

    }


    /**
     * 从底层服务请求数据
     *
     * @param string $url
     * @param string $data
     * @param string $debug
     * @return boolean|Ambigous <>
     */
    public function get_from_api($url = '', $data = '', $debug = false){
        if(empty($url) && empty($data)){
            return false;
        }
        $result = $this->http_request($url, $data, $debug );
        if($debug)
        {
            echo $result;exit;
        }
        if($result['status'] == C('status.success.value')){
            return $result;
        }
        else{
            return false;
        }
    
    }


    /**
     * 通用的HTTP请求工具
     * @author 1034487709@qq.com
     * @param string $path 接口请求path
     * @param unknown $data get|post请求数据
     * @param string $debug 接口的debug模式， 为true将会把数据原包返回
     * @param string $method 请求方式，默认POST
     * @param unknown $cookie 接口请求的cookie信息，用于需要登陆验证的接口
     * @param unknown $multi 文件信息
     * @param unknown $headers 附加的头文件信息
     * @ruturn return_type 返回string 或者 array
     */
    public function http_request($path = '',$data = array(),$debug=false, $method ='POST',$cookie = array(),$multi = array(),$headers = array()){
        $this->benchmark->mark('start');//start clock....
        
        $api_url = $this->create_url($path);

        $response = $this->get_response($api_url,$data,$method,$cookie,$multi,$headers);

        if ($debug === true) {
            return $response;
        }else{
            $response = json_decode($response,true);
        }
        
        $this->benchmark->mark('end');//end clock....
        
        $this->log_message($api_url,$response);
        
        return $response;
   }


   /**
     * 从接口获取数据，根据情况判断从数据库获取还是从memcache获取
     * @param string $path 接口请求path
     * @param unknown $data get|post请求数据
     * @param string $debug 接口的debug模式， 为true将会把数据原包返回
     * @param string $method 请求方式，默认POST
     * @param unknown $cookie 接口请求的cookie信息，用于需要登陆验证的接口
     * @param unknown $multi 文件信息
     * @param unknown $headers 附加的头文件信息
     * @author mochaokai@global28.com
     */
    private function get_response($api_url,$data,$method,$cookie,$multi,$headers){
        $url = $this->get_url($api_url).json_encode($data);

        //判断客户端是否支持memcached和memcached开关是否打开，memcached开关在memcached配置文件中
        if(class_exists('memcached') && C('mymemcached.switch')){
            $this->load->library('Mymemcache');
            $response = Mymemcache::get($url);
            if(!$response){
                $response = Http::Request($api_url,$data,$method,$cookie,$multi,$headers);
                Mymemcache::set($url, $response, C('mymemcached.time'));
                $all_key_arr = Mymemcache::get(C('mymemcached.all_keys'));
                if(!$all_key_arr){
                    $all_key_arr = [];
                }
                if(!in_array($url, $all_key_arr)){
                    $all_key_arr[] = $url;
                    Mymemcache::set(C('mymemcached.all_keys'), $all_key_arr, C('mymemcached.time'));
                }
                return $response;
            }else{
                return $response;
            }
        }else{
            return Http::Request($api_url,$data,$method,$cookie,$multi,$headers);
        }
    }


    /**
     * 对memcache的键进行规范化处理
     * @author mochaokai@global28.com
     * @param string $url
     * @return string $str
     */
    private function get_url($url){
        $str = 'http://';
        foreach (explode('/', $url) as $k => $v){
            if(!empty($v) && $k > 0){
                $str .= $v.'/';
            }
        }
        return $str;
    }

    /**
     * 1034487709@qq.com
     * 操作错误跳转
     * @param string $message 错误信息
     * @param string $jumpUrl 页面跳转地址
     */
    public function error($message='',$jumpUrl='') {
        if(is_array($message))
        {
            $message = implode('<br>',$message);
        }
        $this->dispatchJump($message,0,$jumpUrl);
    }


    /**
     * 1034487709@qq.com
     * 操作成功跳转
     * @param string $message 错误信息
     * @param string $jumpUrl 页面跳转地址
     */
    public function success($message='',$jumpUrl='') {
        $this->dispatchJump($message,1,$jumpUrl);
    }

    /**
     * 1034487709@qq.com
     * 默认跳转操作 支持错误导向和正确跳转
    * @param string $message 提示信息
     * @param Boolean $status 状态
     * @param string $jumpUrl 页面跳转地址
     * @access private
     * @return void
     */
    private function dispatchJump($message,$status=1,$jumpUrl='') {
        $data = $this->data;
        $data['title'] = array("信息提示");
        // 提示标题
       if($status) { //发送成功信息

            $data['message'] = $message ? $message : "操作成功";
            // 成功操作后默认停留1秒
             $data['waitSecond'] = 3 ;
            // 默认操作成功自动返回操作前页面
               if($jumpUrl){
                   $data['jumpUrl'] = $jumpUrl;
               }else{
                   $data['jumpUrl'] = $_SERVER["HTTP_REFERER"];
               }

             $this->load->view("common/msg",$data);
             $this->output->_display();
             die();
        }else{

             $data['message'] = $message ? $message : "操作失败";
            //发生错误时候默认停留3秒
             $data['waitSecond'] = 3;
            // 默认发生错误的话自动返回上页

             $data['jumpUrl'] = "javascript:history.back(-1);";
             $this->load->view("common/msg",$data);
             $this->output->_display();
             die();
        }
    }

    
    /**
     * 发送信息到组
     * @param array $data =['group_id'=> '', 'message' => ''],
     *             |$data =['uid'=> '', 'message' => '']
     * @return string success|error
     */
    public function send($data){
        require_once '../../../workerman/gatewayWorker/Gateway.php';
        // 设置GatewayWorker服务的Register服务ip和端口，请根据实际情况改成实际值
        Gateway::$registerAddress = '127.0.0.1:1238';
        
        // 向任意群组的网站页面发送数据
        if(isset($data['group_id'])){
            Gateway::sendToGroup($data['group_id'], json_encode(['type' => 'msg', 'message' => $data['message']]));
        }else{
            $uid = Gateway::getClientIdByUid($data['uid']);
            if(!$uid){
                return 'error';
            }
            Gateway::sendToUid($uid, json_encode(['type' => 'msg', 'message' => $data['message']]));
        }
        return 'success';
    }
    
    /**
     * 检查客户端请求的token是否合法
     * @param unknown $token
     * @return boolean
     */
    public function doCheckToken($token){
        if(!$this->checkToken($token)){
           $this->return_json(['code' => -1, '登录信息已过期，请重新登录']); 
        }
    }
    
    /**
     * 检查客户端请求的token是否合法
     * @param unknown $token
     * @return boolean
     */
    private function checkToken($token){
        $token = decrypt($token);
        if(isset($token['user_id'])){
            if($token['expires'] > time()){
                return TRUE;
            }
        }
        return FALSE;
    }

}













