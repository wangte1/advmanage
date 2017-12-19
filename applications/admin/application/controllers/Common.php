<?php 
/**
* 首页控制器
* @author jianming@gz-zc.cn
*/
defined('BASEPATH') or exit('No direct script access allowed');
class Common extends MY_Controller{

    public function __construct(){
        parent::__construct();
        $this->load->model([
            'Model_admins' => 'Madmins',
         ]);
        $this->load->library('encryption');

    }
    

    /**
     * 右边内容
     */
    public function index() {

        $this->load->view("common/index");
    }

    /**
     * 顶部内容
     */
    public function top() {
        $data = $this->data;
        $data['user_info'] = $this->data['userInfo'];
        $this->load->view("common/top",$data);
    }

    /*
     *  菜单
     *  1034487709@qq.com
     */
    public function left() {
        $data = $this->data;
        $data['menu'] = $this->Madmins->getMenus();
        $data['admin_id'] = urlencode($this->encryption->encrypt($data['userInfo']['id']));

        $this->load->view("common/left",$data);

    }



    /**
     * 底部内容
     */
    public function bottom() {
        $this->load->view("common/bottom");
    }
    
}
?>
