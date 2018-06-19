<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
 * @author yonghau
 * desc:后台登陆
 * 254274509@qq.com
 */

class App extends MY_Controller {
    private $token;
    public function __construct() {
        parent::__construct();
        
        $this->token = trim($this->input->get_post('token'));
        $this->doCheckToken($this->token);
        
        $this->load->model([
            'Model_houses_app' => 'Mhouses_app',
            'Model_app_location' => 'Mapp_location'
        ]);
    }
    
    public function checkVersion(){
        $v = $this->input->get_post('version');
        $info = $this->Mhouses_app->get_lists("*", ['is_del' => 0], ['version' => 'desc'], 1)[0];
        if($v < $info['version']){
            $this->return_json(['code' => 1, 'url' => $info['url']]);
        }
        $this->return_json(['code' => 0, 'url' => '']);
    }
    
    /**
     * 更新、提交用户所在位置
     */
    public function upUserLocation(){
        
        $addr = $this->input->get_post('addr');
        $longitude= $this->input->get_post('longitude');
        $latitude = $this->input->get_post('latitude');
        
        if(empty($img_url)) $this->return_json(['code' => 0, 'msg' => '请上传图片']);
        $up = [
            'addr' => $addr,
            'user_id' => decrypt($this->token)['user_id'],
            'longitude' => $longitude,
            'latitude' => $latitude,
            'create_time' => time(),
            'date' => date('Y-m-d'),
        ];
        $res = $this->Mapp_location->create($up);
        if(!$res) $this->return_json(['code' => 0, 'msg' => '提交失败']);
        $this->return_json(['code' => 0, 'msg' => '操作成功']);
    }
}