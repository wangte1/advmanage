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
    
    /**
     * app修复
     */
    public function reset(){
        $token = decrypt($this->token);
        $id = $this->input->get_post('id');
        $count = $this->Mhouses_points_report->count(['id' => $id, 'repair_time' => 0]);
        if(!$count) $this->return_json(['code' => 0, 'msg' => '请勿重复提交']);
        $is_new_code = (int) $this->input->post('is_new_code');
        $new_code = $this->input->post('new_code');
        $remarks = $this->input->post('remarks');
        if($is_new_code){
            if(empty($new_code)){
                $this->return_json(['code' => 0, 'msg' => '请填编号！']);
            }
        }
        
        $repair_img = $this->input->post('repair_img');
        if(!$repair_img) $repair_img = "";
        
        $up = [
            'repair_img' => $repair_img,
            'repair_time' => strtotime(date('Y-m-d')),
            'remarks' => $remarks,
            'repair_id' => $token['user_id']
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
                'update_user' =>$token['user_id']
            ];
            $res = $this->Mhouses_points->update_info($point_up, ['id' => $info['point_id']]);
            if(!$res) $this->write_log($token['user_id'], 2, "已修复，但无法更新点位数据id:".$info['point_id']."数据：".json_encode($point_up));
        }
        if($is_new_code){
            $res = $this->Mhouses_points->update_info(['code' => $new_code], ['id' => $info['point_id']]);
            if(!$res) $this->write_log($token['user_id'], 2, "已修复，但无法更新点位数据id:".$info['point_id']."编号为：".$new_code);
            $this->write_log($token['user_id'], 2, "将点位id:".$info['point_id']."编号更改为：".$new_code);
        }
        $this->return_json(['code' => 1, 'msg' => '操作成功']);
    }

}