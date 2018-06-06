<?php
/**
 * 点位状态管理控制器
 * 867332352@qq.com
 */
defined('BASEPATH') or exit('No direct script access allowed');
class Housesstatus extends MY_Controller{

    public function __construct(){
        parent::__construct();
        $this->load->model([
            'Model_houses_points' => 'Mhouses_points',
         ]);
        $this->data['code'] = 'community_manage';
        $this->data['active'] = 'houses_status_list';
    }

    /*
    * 列表
    * 1034487709@qq.com
    */
    public function index(){
        $data = $this->data;
        $data['title'] = array("点位状态","饼形图");
        $data['count1'] = (int) $this->Mhouses_points->count(['point_status' => 1, 'is_del' =>0]);
        $data['count3'] = (int) $this->Mhouses_points->count(['point_status' => 3, 'is_del' =>0]);
        $data['count4'] = (int) $this->Mhouses_points->count(['point_status' => 4, 'is_del' =>0]);
        $data['sum'] = $data['count1'] + $data['count3'] + $data['count4'];
        $data['type1'] = (int) $this->Mhouses_points->count(['type_id' => 1, 'is_del' =>0]);
        $data['type2'] = (int) $this->Mhouses_points->count(['type_id' => 2, 'is_del' =>0]);
        $data['typesum'] = $data['type1'] + $data['type2'];
        $this->load->view("housesstatus/index",$data);
    }
}