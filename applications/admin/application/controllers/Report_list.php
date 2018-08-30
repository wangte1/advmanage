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
            'Model_houses_area' => 'Mhouses_area',
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
        $install = C('install.install');
        $this->load->library('pagination');
        $page =  intval($this->input->get("per_page",true)) ?  : 1;
        $size = $pageconfig['per_page'];
        $where = ['B.is_del' => 0];
        $repair_time= $this->input->get('repair_time');
        $houses_id = $this->input->get('houses_id');
        $area_id = $this->input->get('area_id');
        $usable = $this->input->get('usable');
        $report = $this->input->get('report');
        $start_time = $this->input->get('start_time');
        $end_time = $this->input->get('end_time');
        $r_start_time = $this->input->get('r_start_time');
        $r_end_time = $this->input->get('r_end_time');
        $create_id = $this->input->get('create_id');
        $rcode = trim($this->input->get('rcode'));
        $install_id = $this->input->get('install');
        $addr = $this->input->get('addr');
        $format = $this->input->get('format');
        if($repair_time == "1"){
            $where['A.repair_time >'] = 0;
            $data['repair_time'] = $repair_time;
        }elseif ($repair_time == "2"){
            $data['repair_time'] = $repair_time;
        }elseif ($repair_time == "0"){
            $where['A.repair_time'] = 0;
            $data['repair_time'] = 0;
        }else{
            $data['repair_time'] = 2;
        }
        if($report)$where['report'] = $report;
        if($houses_id) {
            $where['B.houses_id'] = $houses_id;
            $data['houses_id'] = $houses_id;
        }
        if($area_id) {
            $where['B.area_id'] = $area_id;
            $data['area_id'] = $area_id;
        }
        if($usable != '-1' && $usable != null){
            $where['usable'] = $usable;
            $data['usable'] = $usable;
        }
        if($start_time){
            if($end_time){
                $where['A.create_time>='] = strtotime($start_time);
                $data['start_time'] = $start_time;
            }else{
                $where['A.create_time'] = strtotime($start_time);
                $data['start_time'] = $start_time;
            }
        }
        if($end_time){
            $where['A.create_time<='] = strtotime($end_time);
            $data['end_time'] = $end_time;
        }
        if($r_start_time){
            if($r_end_time){
                $where['A.repair_time>='] = strtotime($r_start_time);
                $data['r_start_time'] = $r_start_time;
            }else{
                $where['A.repair_time'] = strtotime($r_start_time);
                $data['r_start_time'] = $r_start_time;
            }
        }
        if($r_end_time){
            $where['A.repair_time<='] = strtotime($r_end_time);
            $data['r_end_time'] = $r_end_time;
        }
        if($create_id){
            $where['A.create_id'] = $create_id;
            $data['create_id'] = $create_id;
        }
        if($install_id){
            $where['C.install'] = $install_id;
            $data['install'] = $install_id;
        }
        if($rcode){
            $where['B.code'] = $rcode;
            $data['rcode'] = $rcode;
        }
        if($addr){
            $where['B.addr'] = $addr;
            $data['addr'] = $addr;
        }
        if($format){
            $where['B.type_id'] = $format;
            $data['format'] = $format;
        }
        $data['report_id'] = $report;
        $data['report'] = C('housespoint.report');
        $data['hlist'] = $this->Mhouses->get_lists();
        if(isset($data['houses_id'])){
            if($data['houses_id']) $data['area_list'] = $this->get_area_info($data['houses_id']);
        }
        $adminList = $this->Madmins->get_lists('id, fullname');
        $data['adminList'] = $adminList;
        $list = $this->Mhouses_points_report->get_report_list($where, ['A.create_time' => 'desc', 'A.id' => 'desc'], $size, ($page-1)*$size);
        if($list){
            foreach ($list as $k => $v){
                $list[$k]['fullname'] = '';
                $list[$k]['point'] = '';
                foreach ($install as $k2 => $v2){
                    if($list[$k]['install_id'] == $k2){
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
                        if($v['repair_id'] == $val['id']){
                            $list[$k]['repair_name'] = $val['fullname'];
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
        $data_count = $this->Mhouses_points_report->get_report_list($where, ['A.create_time' => 'desc', 'A.id' => 'desc'], 0, 0);
        $data_count = count($data_count);
        $data['data_count'] = $data_count;
        $pageconfig['base_url'] = "/report_list/index";
        $pageconfig['total_rows'] = $data_count;
        $this->pagination->initialize($pageconfig);
        $data['pagestr'] = $this->pagination->create_links(); // 分页信息
        $this->load->view('report_list/index', $data);
    }
    
    /**
     * 点位表按行政区域、楼盘分组详情
     */
    public function points_detail($point_id) {
        $data = $this->data;
        $pageconfig = C('page.page_lists');
        $install = C('install.install');
        $this->load->library('pagination');
        $page =  intval($this->input->get("per_page",true)) ?  : 1;
        $size = $pageconfig['per_page'];
        $where['point_id'] = $point_id;
        
        $adminList = $this->Madmins->get_lists('id, fullname');
        $data['adminList'] = $adminList;
        $list = $this->Mhouses_points_report->get_report_list($where, ['A.create_time' => 'desc', 'A.id' => 'desc'], $size, ($page-1)*$size);
        if($list){
            foreach ($list as $k => $v){
                $list[$k]['fullname'] = '';
                $list[$k]['point'] = '';
                foreach ($install as $k2 => $v2){
                    if($list[$k]['install_id'] == $k2){
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
                        if($v['repair_id'] == $val['id']){
                            $list[$k]['repair_name'] = $val['fullname'];
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
        
//         $data['list'] = $this->Mhouses_points_report->get_lists('*',$where);
        $data_count = count($data['list']);
        $data['page'] = $page;
        $data['data_count'] = count($data_count);
        
        //获取分页
        $pageconfig['base_url'] = "/report_list/points_detail/{$point_id}";
        $pageconfig['total_rows'] = $data['data_count'];
        $this->pagination->initialize($pageconfig);
        $data['pagestr'] = $this->pagination->create_links(); // 分页信息
        
        $this->load->view('report_list/points_detail', $data);
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
     * 提交修复
     */
    public function report_add(){
        $data = $this->data;
        if(IS_POST){
            $id = $this->input->post('id');
            
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
                'repair_id' => $data['userInfo']['id']
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
                    'order_id' => "",
                    'lock_num' => 0,
                    'ad_use_num' => 0,
                    'customer_id' => 0,
                    'update_time' => date('Y-m-d H:i:s'),
                    'update_user' =>$data['userInfo']['id']
                ];
                $res = $this->Mhouses_points->update_info($point_up, ['id' => $info['point_id']]);
                if(!$res) $this->write_log($data['userInfo']['id'], 2, "已修复，但无法更新点位数据id:".$info['point_id']."数据：".json_encode($point_up));
            }
            if($is_new_code){
                $res = $this->Mhouses_points->update_info(['code' => $new_code], ['id' => $info['point_id']]);
                if(!$res) $this->write_log($data['userInfo']['id'], 2, "已修复，但无法更新点位数据id:".$info['point_id']."编号为：".$new_code);
                $this->write_log($data['userInfo']['id'], 2, "{$data['userInfo']['fullname']}将点位id:".$info['point_id']."编号更改为：".$new_code);
            }
            $this->return_json(['code' => 1, 'msg' => '操作成功']);
        }
    }
    
    /*
     * 获取楼盘区域信息
     */
    public function get_area_info($houses_id = 0) {
        $where['is_del'] = 0;
        if ($houses_id) $where['houses_id'] = $houses_id;
        $list = $this->Mhouses_area->get_lists('id,name',$where);
        return $list;
    }
    
    
    public function out_excel(){
        $data = $this->data;
        $pageconfig = C('page.page_lists');
        $install = C('install.install');
        $this->load->library('pagination');
        $page =  intval($this->input->get("per_page",true)) ?  : 1;
        $size = $pageconfig['per_page'];
        $where = ['B.is_del' => 0];
        $repair_time= $this->input->get('repair_time');
        $houses_id = $this->input->get('houses_id');
        $area_id = $this->input->get('area_id');
        $usable = $this->input->get('usable');
        $report = $this->input->get('report');
        $start_time = $this->input->get('start_time');
        $end_time = $this->input->get('end_time');
        $r_start_time = $this->input->get('r_start_time');
        $r_end_time = $this->input->get('r_end_time');
        $create_id = $this->input->get('create_id');
        $rcode = trim($this->input->get('rcode'));
        $install_id = $this->input->get('install');
        $addr = $this->input->get('addr');
        $format = $this->input->get('format');
        if($repair_time == "1"){
            $where['A.repair_time >'] = 0;
            $data['repair_time'] = $repair_time;
        }elseif ($repair_time == "2"){
            $data['repair_time'] = $repair_time;
        }elseif ($repair_time == "0"){
            $where['A.repair_time'] = 0;
            $data['repair_time'] = 0;
        }else{
            
        }
        if($report)$where['report'] = $report;
        if($houses_id) {
            $where['B.houses_id'] = $houses_id;
            $data['houses_id'] = $houses_id;
        }
        if($area_id) {
            $where['B.area_id'] = $area_id;
            $data['area_id'] = $area_id;
        }
        if($usable != '-1' && $usable != null){
            $where['usable'] = $usable;
            $data['usable'] = $usable;
        }
        if($start_time){
            if($end_time){
                $where['A.create_time>='] = strtotime($start_time);
                $data['start_time'] = $start_time;
            }else{
                $where['A.create_time'] = strtotime($start_time);
                $data['start_time'] = $start_time;
            }
        }
        if($end_time){
            $where['A.create_time<='] = strtotime($end_time);
            $data['end_time'] = $end_time;
        }
        if($r_start_time){
            if($r_end_time){
                $where['A.repair_time>='] = strtotime($r_start_time);
                $data['r_start_time'] = $r_start_time;
            }else{
                $where['A.repair_time'] = strtotime($r_start_time);
                $data['r_start_time'] = $r_start_time;
            }
        }
        if($r_end_time){
            $where['A.repair_time<='] = strtotime($r_end_time);
            $data['r_end_time'] = $r_end_time;
        }
        if($create_id){
            $where['A.create_id'] = $create_id;
            $data['create_id'] = $create_id;
        }
        if($install_id){
            $where['C.install'] = $install_id;
            $data['install'] = $install_id;
        }
        if($rcode){
            $where['B.code'] = $rcode;
            $data['rcode'] = $rcode;
        }
        if($addr){
            $where['B.addr'] = $addr;
            $data['addr'] = $addr;
        }
        if($format){
            $where['B.type_id'] = $format;
            $data['format'] = $format;
        }
        $data['report_id'] = $report;
        $data['repair_time'] = $repair_time;
        $data['report'] = C('housespoint.report');

        $data['hlist'] = $this->Mhouses->get_lists();
        $list = $this->Mhouses_points_report->get_report_list($where, ['A.create_time' => 'desc', 'A.id' => 'desc'], 0, 0);
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
        
        if($list){
            foreach ($list as $key => $val){
                if($val['point']['addr'] == 1){
                    $list[$key]['point']['addr'] = '门禁';
                }else{
                    $list[$key]['point']['addr'] = '电梯前室';
                }
                if($val['point']['type_id'] == 1){
                    $list[$key]['point']['type'] = '冷光灯箱';
                }else{
                    $list[$key]['point']['type'] = '广告机';
                }
            }
                
        }
        //加载phpexcel
        $this->load->library("PHPExcel");
        
        $table_header =  array(
            '点位编号'=>"code",
            '类型' => 'type',
            '行政区域'=>"area",
            '所属楼盘'=>"houses_name",
            '所属组团'=>"houses_area_name",
            '楼栋'=>"ban",
            '单元'=>"unit",
            '楼层'=>"floor",
            '位置'=>"addr",
            '报损人' => 'fullname',
            '报损时间' => 'create_time',
            '修复时间' => 'repair_time',
            '报损类型' => "report",
            '报损描述' => "report_msg",
            '安装公司'=>"install_id",
            '备注' => 'remarks'
        );
        
        
        $i = 0;
        foreach($table_header as  $k=>$v){
            $cell = PHPExcel_Cell::stringFromColumnIndex($i).'1';
            $this->phpexcel->setActiveSheetIndex(0)->setCellValue($cell, $k);
            $i++;
        }
        
        //填充数据
        $h = 2;
        foreach($list as $key => $val){
            $j = 0;
            foreach($table_header as $k => $v){
                $cell = PHPExcel_Cell::stringFromColumnIndex($j++).$h;
                if(in_array($v, ['report','report_msg', 'fullname', 'create_time', 'repair_time'])){
                    if($v == 'report'){
                        $tmp = explode(',', $val['report']);
                        $value = '';
                        foreach ($tmp as $k1 => $v1){
                            if($v1){
                                $value .= C('housespoint.report')[$v1].',';
                            }
                        }
                    }else if($v == 'create_time'){
                        $value = date('Y-m-d', $val[$v]);
                    }else if ($v == 'repair_time'){
                        $value = date('Y-m-d',$val[$v]);
                    }else{
                        $value = $val[$v];
                    }
                }else{
                    $value = $val['point'][$v];
                    if($v == 'install_id' && $val[$v]){
                        $value = C('install.install')[$val[$v]];
                    }
                }
                $this->phpexcel->getActiveSheet(0)->setCellValue($cell, $value);
            }
            $h++;
        }
        
        $this->phpexcel->setActiveSheetIndex(0);
        // 输出
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename='.date('Ymd').'-报损表.xls');
        header('Cache-Control: max-age=0');
        
        $objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel5');
        $objWriter->save('php://output');
    }
}