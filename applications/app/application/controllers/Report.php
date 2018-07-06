<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
 * @author yonghua
 * desc:报损列表
 * 254274509@qq.com
 */

class Report extends MY_Controller {

    public function __construct() {
        parent::__construct();
        
        $this->token = trim($this->input->get_post('token'));
        $this->doCheckToken($this->token);
        
        $this->load->model([
            'Model_admins' => 'Madmins',
            'Model_houses_points' => 'Mhouses_points',
            'Model_houses_area' => 'Mhouses_area',
            'Model_houses' => 'Mhouses',
            'Model_houses_points_report' => 'Mhouses_points_report',
        ]);
    }
    
    /**
     * 报损列表首页，显示楼盘， 个数
     */
    public function index(){
        $list = $this->Mhouses_points_report->get_report_houses_list(['A.repair_time' => 0]);
        if(!$list){
            $this->return_json(['code' => 0, 'data' => [], 'msg' => "暂无数据"]);
        }
        $this->return_json(['code' => 1, 'data' => $list, 'msg' => "ok"]);
    }
    
    /**
     * 报损列表首页，显示楼盘， 个数
     */
    public function detail(){
        $houses_id = $this->input->get_post('houses_id');
        $list = $this->Mhouses_points_report->get_report_list(['A.repair_time' => 0, 'C.id' => $houses_id]);
        if($list){
            foreach ($list as $k => $v){
                $install = C('install.install');
                $list[$k]['install_company'] = "未设置";
                if($v['install']){
                    $list[$k]['install_company'] = $install[$v['install']];
                }
                $list[$k]['create_time'] = date('Y-m-d', $v['create_time']);
            }
        }
        if(!$list){
            $this->return_json(['code' => 0, 'data' => [], 'msg' => "暂无数据"]);
        }
        $this->return_json(['code' => 1, 'data' => $list, 'msg' => "ok"]);
    }

}