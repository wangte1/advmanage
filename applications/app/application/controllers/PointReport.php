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
        $this->token = trim($this->input->get_post('token'));
        $this->doCheckToken($this->token);
        $this->load->model([
            'Model_houses_points_report' => 'Mhouses_points_report',
            'Model_admins' => 'Madmins'
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
//         echo $this->db->last_query();die;
        $admin_list = $this->Madmins->get_lists("id,name",['is_del'=>1]);
        if($list&&$admin_list){
            foreach ($list as $k=>$v){
                $list[$k]['create_name'] = '';
                $list[$k]['report_name'] = ''; 
                $list[$k]['create_time'] = date('Y-m-d', $v['create_time']);
                if ($list[$k]['repair_time']!=0){
                    $list[$k]['repair_time'] = date('Y-m-d', $v['repair_time']);
                }
                foreach ($admin_list as $key => $val){
                    if($val['id'] == $v['create_id']){
                        $list[$k]['create_name'] = $val['name'];
                    }
                    if($val['id'] == $v['repair_id']){
                        $list[$k]['report_name'] = $val['name'];
                }        
            } 
        }  
        $this->return_json(['code' => 1, 'list' =>  $list, 'msg' => "ok"]);    
      }
        $this->return_json(['code' => 0, 'list' => [], 'msg' => "null"]);
    }
}