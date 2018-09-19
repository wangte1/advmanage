<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
 * @author tete
 * desc:维修列表相关信息
 * 1517796462@qq.com
 */

class PointReport extends MY_Controller {
    private $token;
    public function __construct() {
        parent::__construct();
//         $this->token = trim($this->input->get_post('token'));
//         $this->doCheckToken($this->token);
        $this->load->model([
            'Model_houses_points_report' => 'Mhouses_points_report',
            'Model_admins' => 'Madmins',
            'Model_houses_points'=>'Mhouses_points'
        ]);
    }

    /**
     * 报修列表相关信息
     */
    public  function index(){
        $pageconfig = C('page.page_lists');
        $this->load->library('pagination');
        $page = (int) $this->input->get_post('page') ? : '1';
        $size = (int) $this->input->get_post('size');
        if(!$size) $size = $pageconfig['per_page'];
        $point_id = (int) $this->input->get_post('point_id');
        $where = ['point_id'=> $point_id];
        $orderBy = ['id' => 'asc'];
        $list = $this->Mhouses_points_report->get_lists("report_img,repair_img,create_id,repair_id,create_time,repair_time,report_msg,remarks",$where, $orderBy, $size, ($page-1)*$size );
        $repair_ids = array_unique(array_column($list, 'repair_id')); 
        $create_ids = array_unique(array_column($list, 'create_id'));
        $all_admin_ids = array_merge($create_ids, $repair_ids);
        $adminList = $this->Madmins->get_lists("id,fullname",['in' => ['id' => $all_admin_ids]]);
        if($list && $adminList){
            foreach ($list as $k=>$v){
                $list[$k]['create_name'] = '';
                $list[$k]['report_name'] = ''; 
                $list[$k]['create_time'] = date('Y-m-d', $v['create_time']);
                $list[$k]['repair_img'] = get_adv_img($v['repair_img']);
                $list[$k]['report_img'] = get_adv_img($v['report_img']);
                if ($list[$k]['repair_time']!=0){
                    $list[$k]['repair_time'] = date('Y-m-d', $v['repair_time']);
                }
                foreach ($adminList as $key => $val){
                    if($val['id'] == $v['create_id']){
                        $list[$k]['create_name'] = $val['fullname'];
                    }  
                    if($val['id'] == $v['repair_id']){
                        $list[$k]['report_name'] = $val['fullname'];
                    } 
            }      
        }  
        $this->return_json(['code' => 1, 'list' =>  $list, 'msg' => "ok"]);    
      }
        $this->return_json(['code' => 0, 'list' => [], 'msg' => "null"]);
    }
    
    /**
     * 报修列表
     */
    public function reportlist(){
        $pageconfig = C('page.page_lists');
        $this->load->library('pagination');
        $page = (int) $this->input->get_post('page') ? : '1';
        $size = (int) $this->input->get_post('size');
        if(!$size) $size = $pageconfig['per_page'];
//         $repair_id = (int) $this->input->get_post('repair_id');
        $token1 = decrypt($token);
        $repair_id = $token1['user_id'];
        $where = ['repair_id'=> $repair_id,"repair_time >"=>0];
        $orderBy = ['id' => 'asc'];
        $reportpoint_list = $this->Mhouses_points->get_report_points("A.*,B.repair_id", $where, $orderBy, $size, ($page-1)*$size );
        if($reportpoint_list){
            $this->return_json(['code' => 1, 'list' =>  $reportpoint_list, 'msg' => "ok"]);    
      }
            $this->return_json(['code' => 0, 'list' => [], 'msg' => "null"]);
    }
}