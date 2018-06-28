<?php
/**
 * 报损列表
 * 254274509@qq.com
 */
defined('BASEPATH') or exit('No direct script access allowed');
class Report_list extends MY_Controller{

    public function __construct(){
        parent::__construct();
        $this->load->model([
            'Model_houses' => 'Mhouses',
            'Model_admins' => 'Madmins',
            'Model_houses_points' => 'Mhouses_points',
            'Model_houses_points_report' => 'Mhouses_points_report',
        ]);
        $this->pageconfig = C('page.page_lists');
        $this->load->library('pagination');
        $this->data['code'] = 'community_manage';
        $this->data['active'] = 'houses_report_list';
    }
    
    public function index(){
        $data = $this->data;
        $pageconfig = C('page.page_lists');
        $this->load->library('pagination');
        $page =  intval($this->input->get("per_page",true)) ?  : 1;
        $size = $pageconfig['per_page'];
        $where = [];
        $repair_time= $this->input->get('repair_time');
        $houses_id = $this->input->get('houses_id');
        $report = $this->input->get('report');
        if($repair_time){
            switch ((int)$repair_time){
                case 1:
                    $where['A.repair_time !='] = 0;
                    break;
            }
        }else{
            $where['A.repair_time'] = 0;
        }
        if($report)$where['like'] = ['report' => $report . ','];
        if($houses_id) {
            $where['B.houses_id'] = $houses_id;
            $data['houses_id'] = $houses_id;
        }
        $data['report_id'] = $report;
        $data['repair_time'] = $repair_time;
        $data['report'] = C('housespoint.report');
        $data['hlist'] = $this->Mhouses->get_lists();
        $list = $this->Mhouses_points_report->get_report_list($where, ['A.create_time' => 'desc', 'A.id' => 'desc'], $size, ($page-1)*$size, ['A.point_id']);
        if($list){
            foreach ($list as $k => $v){
                $list[$k]['fullname'] = '';
                $list[$k]['point'] = '';
            }
            //获取报损人ids
            $admin_ids = array_unique(array_column($list, 'create_id'));
            $adminList = $this->Madmins->get_lists('id, fullname', ['in' => ['id' => $admin_ids]]);
            if($adminList){
                foreach ($list as $k => $v){
                    foreach ($adminList as $key => $val){
                        if($v['create_id'] == $val['id']){
                            $list[$k]['fullname'] = $val['fullname'];
                        }
                    }
                }
            }
            //提取点位ids
            $point_ids = array_unique(array_column($list, 'point_id'));
            $pointList = $this->Mhouses_points->get_points_lists(['in' => ['A.id' => $point_ids]]);
            if($pointList){
                foreach ($list as $k => $v){
                    foreach ($pointList as $key => $val){
                        if($v['point_id'] == $val['id']){
                            $list[$k]['point'] = $val;
                        }
                    }
                }
            }
        }
        
        $data['list'] = $list;
        //获取分页
        $data_count = $this->Mhouses_points_report->get_report_list($where);
        $data_count = count($data_count);
        $data['data_count'] = $data_count;
        $pageconfig['base_url'] = "/report_list/index";
        $pageconfig['total_rows'] = $data_count;
        $this->pagination->initialize($pageconfig);
        $data['pagestr'] = $this->pagination->create_links(); // 分页信息
        $this->load->view('report_list/index', $data);
    }
    
    /**
     * 点位报修
     */
    public function report(){
        $data = $this->data;
        $data['id'] = $this->input->get('id');
        $this->load->view("report_list/report",$data);
    }
    
    /**
     * 提交报损
     */
    public function report_add(){
        $data = $this->data;
        if(IS_POST){
            $id = $this->input->post('id');
            
            $count = $this->Mhouses_points_report->count(['id' => $id, 'repair_time' => 0]);
            if(!$count) $this->return_json(['code' => 0, 'msg' => '请勿重复提交']);
            
            $repair_img = $this->input->post('repair_img');
            if(!$repair_img) $this->return_json(['code' => 0, 'msg' => '请上传修复图']);
            
            $up = [
                'repair_img' => $repair_img,
                'repair_time' => strtotime(date('Y-m-d'))
            ];
            $res = $this->Mhouses_points_report->update_info($up, ['id' => $id]);
            if(!$res){
                $this->return_json(['code' => 0, 'msg' => '操作失败，请重试']);
            }
            //获取是否可以上画数据
            $info = $this->Mhouses_points_report->get_one('usable, point_id', ['id' => $id]);
            if($info['usable'] == 0){
                $point_up = [
                    'point_status' => 1,
                    'lock_num' => 0,
                    'ad_use_num' => 0,
                    'customer_id' => 0,
                    'update_time' => date('Y-m-d H:i:s'),
                    'update_user' =>$data['userInfo']['id']
                ];
                $res = $this->Mhouses_points->update_info($point_up, ['id' => $info['point_id']]);
                if(!$res) $this->write_log($data['userInfo']['id'], 2, "已z修复，但无法更新点位数据id:".$info['point_id']."数据：".json_encode($point_up));
            }
            $this->return_json(['code' => 1, 'msg' => '操作成功']);
        }
    }
}