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
        var_dump( $all_admin_ids);
//         echo  $this->db->last_query();exit();
        $adminList = $this->Madmins->get_lists("id,fullname",['in' =>['id' => $all_admin_ids]]);
        echo  $this->db->last_query();exit();
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
     * 我的维修列表
     */
    public function reportlist(){
        $pageconfig = C('page.page_lists');
        $this->load->library('pagination');
        $page = (int) $this->input->get_post('page') ? : '1';
        $size = (int) $this->input->get_post('size');
        if(!$size) $size = $pageconfig['per_page'];
        $repair_id = decrypt($this->token)['user_id'];
        $where = ['A.repair_id'=> $repair_id, "A.repair_time >"=>0];
        $orderBy = ['A.id' => 'asc'];
        $reportpoint_list = $this->Mhouses_points_report->get_report_points("B.code,B.addr,B.houses_name,B.area_name,B.ban,B.unit,B.floor,A.id", $where, $orderBy, $size, ($page-1)*$size );
        if($reportpoint_list){
            foreach ($reportpoint_list as $k=>$v){
                $reportpoint_list[$k]['addr_text']='';
                switch ($v['addr']){
                    case 1:   $reportpoint_list[$k]['addr_text']='门禁'; break;
                    case 2:   $reportpoint_list[$k]['addr_text']='地面电梯前室';break;
                    case 3:   $reportpoint_list[$k]['addr_text']='地下电梯前室';break;
                    default: $reportpoint_list[$k]['addr_text']='';break;
                }
                unset($reportpoint_list[$k]['addr']);
            }
            $this->return_json(['code' => 1, 'list' =>  $reportpoint_list, 'msg' => "ok"]);
        }
        $this->return_json(['code' => 0, 'list' => [], 'msg' => "null"]);
    }
    /**
     *我的维修列表详情
     */
    public function detail(){
        $id = (int) $this->input->get_post('id');
        $where = ['id'=> $id];
        $detil = $this->Mhouses_points_report->get_one("report_img,repair_img,create_id,repair_id,create_time,repair_time,report_msg,remarks,usable", $where);
        $all_admin_ids=[$detil['create_id'], $detil['repair_id']];
        $adminList = $this->Madmins->get_lists("id,fullname",['in' =>['id' => $all_admin_ids]]);
        if($detil && $adminList){
            $detil['creat_name']='';
            $detil['repair_name']='';
            $detil['create_time'] = date('Y-m-d',  $detil['create_time']);
            $detil['repair_img'] = get_adv_img($detil['repair_img']);
            $detil['report_img'] = get_adv_img($detil['report_img']);
            if ($detil['repair_time'] !=0){
                $detil['repair_time']= date('Y-m-d',  $detil['repair_time']);
            }
            foreach ($adminList as $k=>$v){
                if($v['id'] == $detil['create_id']){
                    $detil['creat_name'] = $v['fullname'];
                }
                if($v['id'] == $detil['repair_id']){
                    $detil['repair_name'] = $v['fullname'];
                }
            }
            $this->return_json(['code' => 1, 'detil' =>  $detil, 'msg' => "ok"]);
        }
        $this->return_json(['code' => 0, 'detil' => [], 'msg' => "null"]);
    }
}
