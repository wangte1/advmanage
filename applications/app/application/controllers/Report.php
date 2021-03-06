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
        //$this->write_log(1, 1, '报损：'.$this->db->last_query());
        if($list){
            $createId = array_column($list, 'create_id');
            $createName = $this->Madmins->get_lists("id,fullname",['in' =>['id'=>$createId]]);
            foreach ($list as $k => $v){
                //获取保修人的名称
                foreach($createName as $n=>$m)
                {
                    if($m['id']==$v['create_id'])
                    {
                        $list[$k]['create_name'] = $m['fullname'];break;
                    }
                }     
                if($v['addr'] == 1){
                    $list[$k]['addr'] = "门禁";
                }    
                else if($v['addr'] == 2)
                {
                    $list[$k]['addr'] = "地面电梯前室";
                }else{
                    $list[$k]['addr'] = "地下电梯前室";
                }
                $install = C('install.install');
                $list[$k]['install_company'] = "未设置";
                if($v['install_id']){
                    $list[$k]['install_company'] = $install[$v['install_id']];
                }
                $list[$k]['create_time'] = date('Y-m-d', $v['create_time']);
                $tmp = explode(',', $v['report']);
                $list[$k]['report_txt'] = '';
                foreach ($tmp as $key => $val){
                    if($val){
                        if($key == 0){
                            if(isset(C('housespoint.report')[$val])){
                                $list[$k]['report_txt'] .= C('housespoint.report')[$val];
                            }
                        }else{
                            if(isset(C('housespoint.report')[$val])){
                                $list[$k]['report_txt'] .= ",".C('housespoint.report')[$val];
                            }
                        }
                    }
                }
            }
        }

        if(!$list){
            $this->return_json(['code' => 0, 'data' => [], 'msg' => "暂无数据"]);
        }
        //提取点位ids，获取点位信息
        $point_ids = array_column($list, 'point_id');
        $plist = $this->Mhouses_points->get_lists('id,houses_id, area_id', ['in' => ['id' => $point_ids]]);
        if($plist){
            foreach ($plist as $k => $v){
                foreach ($list as $key => $val){
                    if($v['id'] == $val['point_id']){
                        $list[$key]['houses_name'] = "";
                        $list[$key]['area_name'] = "无组团";
                    }
                }
            }
            $houses_ids = array_column($plist, 'houses_id');
            //行政区域和楼盘
            $housesList = $this->Mhouses->get_lists('id,name,area', ['in' => ['id' => $houses_ids]]);
            if($housesList){
                foreach ($housesList as $k => $v){
                    foreach ($list as $key => $val){
                        if($v['id'] == $val['houses_id']){
                            $list[$key]['houses_name'] = $v['area'].$v['name'];
                        }
                    }
                }
            }
            //获取这个楼盘、和用户所关联的组团
            $areaList = $this->Mhouses_area->get_lists('id, name, houses_id', ['in' => ['houses_id' => $houses_ids]]);
            if($areaList){
                foreach ($areaList as $k => $v){
                    foreach ($list as $key => $val){
                        if($v['houses_id'] == $val['houses_id'] && $v['id'] == $val['area_id']){
                            $list[$key]['area_name'] = $v['name'];
                        }
                    }
                }
            }
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
                'order_id' => "",
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
    
    /**
     * 返回报损数据
     */
    public function getConfig(){
        $config = C('housespoint.report');
        if($config){
            $data = [];
            foreach ($config as $k => $v){
                $arr = ['index' => $k, 'reason' => $v];
                $data[] = $arr;
            }
            $this->return_json(['code' => 1, 'data' => $data, "msg" => "ok"]);
        }
        $this->return_json(['code' => 0, 'data' => [], "msg" => "无法读取配置文件"]);
    }
    
    /**
     * 我的报损
     */
    public function my(){
        $token = decrypt($this->token);
        $pageconfig = C('page.page_lists');
        $this->load->library('pagination');
        $page = (int) $this->input->get_post('page');
        if(!$page) $page = 1;
        $size = (int) $this->input->get_post('size');
        if(!$size) $size = $pageconfig['per_page'];
        $where = ['A.create_id' => $token['user_id']];
        $order_by = ['A.create_time' => 'desc'];
        $list = $this->Mhouses_points_report->get_my_report_list($where, $order_by, $size, ($page-1)*$size);
        if(!$list){
            $this->return_json(['code' => 0, 'data' => [], 'msg' => "暂无数据"]);
        }
        foreach ($list as $k => $v){
            $tmp = "";
            switch ($v['addr']){
                case 1 :
                    $tmp = "门禁";
                    break;
                case 2 :
                    $tmp = "地面电梯前室";
                    break;
                case 3 :
                    $tmp = "地下电梯前室";
                    break;
            }
            $list[$k]['addr'] = $tmp;
            if(!$v['houses_area_name']){
                $list[$k]['houses_area_name'] = '';
            }
        }
        $this->return_json(['code' => 1, 'data' => $list, 'msg' => "ok"]);
    }
    
    /**
     * 点位报损详情
     * @return unknown
     */
    public function get_report_detail(){
        $token = decrypt($this->token);
        $id = (int) $this->input->get_post('id');
        $info = $this->Mhouses_points_report->getDetailById($id);
        if(!$info) $this->return_json(['code' => 0, 'data' => [], 'msg' => "暂无数据"]);
        $tmp = "";
        switch ($info['addr']){
            case 1 :
                $tmp = "门禁";
                break;
            case 2 :
                $tmp = "地面电梯前室";
                break;
            case 3 :
                $tmp = "地下电梯前室";
                break;
        }
        $info['addr'] = $tmp;
        $admin = $this->Madmins->get_one('fullname', ['id' => $info['create_id']]);
        $info['report_name'] = $admin['fullname'];
        if(!$info['houses_area_name']) $info['houses_area_name'] = "";
        $info['usable'] = "可上画";
        if(!$info['usable']) $info['usable'] = "不可上画";
        $info['report_txt'] = C("housespoint.report")[$info['report']];
        $info['create_time'] = date("Y-m-d", $info['create_time']);
        if($info['report_img']) $info['report_img'] = get_adv_img($info['report_img'], "common");
        return $this->return_json(['code' => 0, 'data' => $info, 'msg' => "OK"]);
    }

}