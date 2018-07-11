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
            'Model_houses' => 'Mhouses',
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
        //
        $list = $this->Mhouses_points->get_lists('houses_id,SUM(used_num) as num',['is_del' => 0], ["num" => 'desc'], 0, 0 ,['houses_id']);
        $total = array_column($list, 'num');
        $total = array_sum($total);
        foreach ($list as $k => $v){
            $list[$k]['v'] = 0.00;
            $list[$k]['houses_name'] = "";
            if($v['num'] > 0){
                $list[$k]['v'] = sprintf("%.6f", $v['num']/$total) * 100;
            }
        }
        $houses_list = $this->Mhouses->get_lists("id, name");
        if($houses_list){
            foreach ($list as $k => $v){
                foreach ($houses_list as $key => $val){
                    if($v['houses_id'] == $val['id']){
                        $list[$k]['houses_name'] = $val['name'];
                        break;
                    }
                }
            }
        }
        $data['houses_list'] = $list;
        $this->load->view("housesstatus/index",$data);
    }
}