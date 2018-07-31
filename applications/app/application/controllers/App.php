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
            'Model_app_location' => 'Mapp_location',
            'Model_app_actions' => 'Mapp_actions'
        ]);
    }
    
    /**
     * 检查app版本
     * @desc 如果版本过低则code 返回1，url为最新的下载地址， 反之则code = 0 无需任何操作；
     * @param version 版本 
     */
    public function checkVersion(){
        $version= $this->input->get_post('version');
        if(empty($version)) $this->return_json(['code' => 0, 'url' => ""]);
        
        $info = $this->Mhouses_app->get_one("MAX(`id`) as id", ['is_del' => 0]);
        if(!$info) $this->return_json(['code' => 0, 'url' => '']);
        //最大版本id
        $id = $info['id'];
        //获取当前版本id
        $nowId = 0;
        $thisInfo = $this->Mhouses_app->get_one('id', ['version' => $version]);
        if($thisInfo) {
            $nowId = $thisInfo['id'];
        }
        if($nowId < $id){
            $max = $this->Mhouses_app->get_one('id, version, url', ['id' => $id]);
            $this->return_json(['code' => 1, 'url' => $max['url']]);
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
        $this->return_json(['code' => 1, 'msg' => '操作成功']);
    }
    
    /**
     * 记录app操作日志
     */
    public function actions(){
        $user = decrypt($this->token);
        $url = trim($this->input->get_post('url'));
        $content = trim($this->input->get_post('content'));
        $add = [
            'user_id' => $user['user_id'],
            'token' => $this->token,
            'url' => $url,
            'content' => $content,
            'year' => date('Y'),
            'month' => date('m'),
            'day' => date('d'),
            'create_time' => time()
        ];
        $res = $this->Mapp_actions->create($add);
        if(!$res) $this->return_json(['code' => 0, 'msg' => '提交失败']);
        $this->return_json(['code' => 1, 'msg' => '操作成功']);
    }
}