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
            'Model_houses' => 'Mhouses',
            'Model_houses_tour_points' => 'Mhouses_tour_points',
        ]);
        $this->data['code'] = 'community_manage';
        $this->data['active'] = 'tour_lists';
    }
    
    public function index(){
        $data = $this->data;
        $pageconfig = C('page.page_lists');
        $this->load->library('pagination');
        $page =  intval($this->input->get("per_page",true)) ?  : 1;
        $size = $pageconfig['per_page'];
        $create_time = $this->input->get('create_time');
        if(empty($create_time)){
            $create_time= date('Y-m-d 00:00:00', time());
        }else{
            $create_time .= ' 00:00:00';
        }
        $data['create_time'] = date("Y-m-d", strtotime($create_time));
        $create_time_end = $this->input->get('create_time_end');
        if(empty($create_time_end)){
            $time = date("Y-m-d 00:00:00", strtotime($create_time)+(24*3600));
            $create_time_end = $time;
        }else{
            $create_time_end .= ' 00:00:00';
        }
        $data['create_time_end'] = date("Y-m-d", strtotime($create_time_end));
        $principal_id = $this->input->get('principal_id');
        if($principal_id) {
            $where['B.id'] = $principal_id;
            $data['principal_id'] =$principal_id;
        }
        $where['A.create_time >'] = $create_time;
        $where['A.create_time <'] = $create_time_end;

        $list = $this->Mhouses_tour_points->get_tour_list($where, ['num' => 'desc'], $size, ($page-1)*$size, ['B.id']);
        //统计该时间段每个人都分布巡视那些楼盘
        $hlist = $this->Mhouses_tour_points->get_houses_lists($where);
        
        $data['list'] = $list;
        if($hlist){
            foreach ($list as $k => $v){
                $data['list'][$k]['houses_name'] = "";
                foreach ($hlist as $key => $val){
                    if($v['id'] == $val['id']){
                        $data['list'][$k]['houses_name'] .= $val['name']."、";
                    }
                }
            }
        }
        $adminList = $this->Madmins->get_lists('id, fullname', ['in' => ['group_id' => [4,6]]]);
        $data['adminList'] = $adminList;
        $count = $this->Mhouses_tour_points->get_tour_list($where, [], 0, 0, ['B.id']);
        $data['data_count'] = count($count);
        $pageconfig['base_url'] = "/housestour/index";
        $pageconfig['total_rows'] = count($count);
        $this->pagination->initialize($pageconfig);
        $data['pagestr'] = $this->pagination->create_links(); // 分页信息
        $this->load->view('housestour/index', $data);
    }
    
    public function detail(){
        $data = $this->data;
        $pageconfig = C('page.page_lists');
        $this->load->library('pagination');
        $page =  intval($this->input->get("per_page",true)) ?  : 1;
        $size = $pageconfig['per_page'];
        $where = [];
        $user_id = $this->input->get('user_id');
        if($user_id){
            $where['A.principal_id'] = $user_id;
        }
        $houses_id = $this->input->get('houses_id');
        if($houses_id){
            $where['D.id'] = $data['houses_id'] = $houses_id;
        }
        $point_code = $this->input->get('point_code');
        if($point_code){
            $where['C.code'] = $data['point_code'] = $point_code;
        }
        $create_time = $this->input->get('create_time');
        if(empty($create_time)){
            $where['A.create_time >=']= date('Y-m-d 00:00:00', time());
        }else{
            $data['create_time'] = $create_time;
            $where['A.create_time >'] = $create_time.' 00:00:00';
        }
        $create_time_end = $this->input->get('create_time_end');
        if(empty($create_time_end)){
            $time = date("Y-m-d 00:00:00", strtotime($create_time)+(24*3600));
            $create_time_end = $time;
        }else{
            $create_time_end .= ' 00:00:00';
        }
        $data['create_time_end'] = date("Y-m-d", strtotime($create_time_end));
        $where['A.create_time <'] = $create_time_end;
        
        $list = $this->Mhouses_tour_points->get_user_all($where, ['A.create_time' => 'desc'], $size, ($page-1)*$size, []);
        if($list){
            foreach ($list as $k => $v){
                switch ($v['addr']){
                    case 1 :
                        $list[$k]['addr'] = '门禁';
                        break;
                    case 2 :
                        $list[$k]['addr'] = '地面电梯前室';
                        break;
                    case 3 :
                        $list[$k]['addr'] = '地下电梯前室';
                        break;
                }
            }
            $data['list'] = $list;
            $count = $this->Mhouses_tour_points->get_user_all($where, [], 0, 0, []);
            $data['data_count'] = count($count);
            $pageconfig['base_url'] = "/housestour/detail";
            $pageconfig['total_rows'] = count($count);
            $this->pagination->initialize($pageconfig);
            $data['pagestr'] = $this->pagination->create_links(); // 分页信息
        }
        $data['hlist'] = $this->Mhouses->get_lists('id,name',['is_del'=>0]);
        $this->load->view('housestour/detail', $data);
    }
}