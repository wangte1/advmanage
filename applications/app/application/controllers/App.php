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
            'Model_houses_app' => 'Mhouses_app'
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
}