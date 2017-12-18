<?php 
/**
* 预定订单管理控制器
* @author yonghua 254274509@qq.com
*/
defined('BASEPATH') or exit('No direct script access allowed');
class Housespreorders extends MY_Controller{

    public function __construct(){
        parent::__construct();
        $this->load->model([
            'Model_houses_preorders' => 'Mhouses_preorders'
        ]);
        $this->data['code'] = 'horders_manage';
        $this->data['active'] = 'housespreorders_list';
    }
    
    /**
     * 预定订单首页
     */
    public function index(){
        $data = $this->data;
        $this->load->view('housespreorders/index', $data);
    }
}