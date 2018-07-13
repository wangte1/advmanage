<?php
/**
 * 点位状态管理控制器
 * 254274509@qq.com
 */
defined('BASEPATH') or exit('No direct script access allowed');
class Housestour extends MY_Controller{

    public function __construct(){
        parent::__construct();
        $this->load->model([
            'Model_houses_tour_points' => 'Mhouses_tour_points',
        ]);
        $this->data['code'] = 'community_manage';
        $this->data['active'] = 'tour_list';
    }
    
    public function index(){
        $data = $this->data;
        $pageconfig = C('page.page_lists');
        $this->load->library('pagination');
        $page =  intval($this->input->get("per_page",true)) ?  : 1;
        $size = $pageconfig['per_page'];
        $create_time = $this->input->get('create_time');
        if(!$create_time){
            $create_time= date('Y-m-d 00:00:00', time());
        }
        $where['A.create_time >'] = $create_time;
        $where['A.create_time <'] = date("Y-m-d 00:00:00", strtotime(date("Y-m-d"))+(24*3600));
        $list = $this->Mhouses_tour_points->get_tour_list($where, ['num' => 'desc'], $size, ($page-1)*$size, ['B.id']);
        $data['list'] = $list;
        $adminList = $this->Madmins->get_lists('id, fullname', ['in' => ['group_id' => [4,6]]]);
        $data['adminList'] = $adminList;
        $count = $this->Mhouses_tour_points->get_tour_list($where);
        $data['data_count'] = count($count);
        $pageconfig['base_url'] = "/report_list/index";
        $pageconfig['total_rows'] = count($count);
        $this->pagination->initialize($pageconfig);
        $data['pagestr'] = $this->pagination->create_links(); // 分页信息
        $this->load->view('housestour/index', $data);
    }
}