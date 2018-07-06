<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @desc 维修控制器
 * @author TT
 *
 */
class Report extends MY_Controller {
    private $token;
    public function __construct() {
        parent::__construct();
        
        $this->token = trim($this->input->get_post('token'));
        $this->doCheckToken($this->token);
        
        $this->load->model([
            'Model_houses_points_report' => 'Mhouses_points_report',
            'Model_admins' => 'Madmins',
            'Model_houses_points' => 'Mhouses_points',
        ]);
    }
    
    public function index(){
        $install = C('install.install');
        $where = [];
        $adminList = $this->Madmins->get_lists('id, fullname');
        $list = $this->Mhouses_points_report->get_report_list($where, ['A.create_time' => 'desc', 'A.id' => 'desc'], 0, 0, ['A.point_id']);
        if($list){
            foreach ($list as $k => $v){
                $list[$k]['fullname'] = '';
                $list[$k]['point'] = '';
                foreach ($install as $k2 => $v2){
                    if($list[$k]['install'] == $k2){
                        $list[$k]['install'] = $v2;
                    }
                }
            }
            
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
        $this->return_json(array("code"=>1, "data"=>$list));
    }
}