<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
 * @author yonghua
 * desc:巡视
 * 254274509@qq.com
 */

class Tour extends MY_Controller {
    private $token;
    private $time = 7*24*3600;
    public function __construct() {
        parent::__construct();
        
        $this->token = trim($this->input->get_post('token'));
        $this->doCheckToken($this->token);
        
        $this->load->model([
            'Model_admins' => 'Madmins',
            'Model_houses_points' => 'Mhouses_points',
            'Model_houses_tour_points' => 'Mhouses_tour_points',
            'Model_houses_area' => 'Mhouses_area',
            'Model_houses' => 'Mhouses',
            'Model_houses_diy_area' => 'Mhouses_diy_area',
            'Model_houses_points_report' => 'Mhouses_points_report',
        ]);
    }
    
    /**
     * 按楼盘排列
     */
    public function index(){
        $data = $this->data;
        //获取用户id,根据id获取用户负责的区域点位
        $user_id = decrypt($this->token)['user_id'];
        $info = $this->Madmins->get_one('diy_area_id', ['id' => $user_id]);
        $diy_area_id = (int) $info['diy_area_id'];
        if($diy_area_id == 0){
            $this->return_json(['code' => 0, 'data' => [], 'msg' => '系统还未给您分配区域']);
        }
        
        //查询用户负责的楼盘列表
        $houses = $this->Mhouses_diy_area->get_lists('houses_id', ['diy_area_id' => $diy_area_id]);
        if(!$houses){
            $this->return_json(['code' => 0, 'data' => [], 'msg' => '您所负责的区域暂无分配楼盘']);
        }
        $houses_ids = array_column($houses, 'houses_id');
        if(count($houses_ids) > 1){
            $houses_ids = array_unique($houses_ids);
            
        }
        //提取楼盘、组团
        $where = ['in' => ['houses_id' => $houses_ids]];
        $where['diy_area_id>'] = 0;
        $group_by = ['houses_id', 'area_id'];
        //获取这个楼盘、和用户所关联的组团
        $areaInfo = $this->Mhouses_diy_area->get_lists('area_id', ['in' => ['houses_id' => $houses_ids], 'diy_area_id' => $diy_area_id]);
        if($areaInfo){
            $area_ids = array_column($areaInfo, 'area_id');
            if($area_ids){
                $area_ids = array_unique($area_ids);
                $where['in']['area_id'] = $area_ids;
            }
        }
        $list = $this->Mhouses_points->get_lists('houses_id, houses_name, area_id, count(id) as num,area_name', $where, ['houses_id' => 'asc'], 0, 0, $group_by);
        
        //提取楼盘ids
        $listData = [];
        if($list){
            foreach ($houses_ids as $k => $v){
                $listData[$k]['houses_id'] = $v;
                $listData[$k]['areas'] = '';
                $listData[$k]['houses_name'] = '';
                $listData[$k]['area'] = [];
                $listData[$k]['num'] = 0;
            }
            //行政区域
            $areaList = $this->Mhouses->get_lists('id,name,city,area', ['in' => ['id' => $houses_ids]]);
            if($areaList){
                foreach ($listData as $k => $v){
                    foreach ($areaList as $key => $val){
                        if($v['houses_id'] == $val['id']){
                            $listData[$k]['houses_name'] = $val['name'];
                            $listData[$k]['areas'] = $val['city'].$val['area'];
                        }
                    }
                }
            }
            foreach ($list as $k => $v){
                foreach ($listData as $key => $val){
                    if($v['houses_id'] == $val['houses_id']){
                        //设置楼盘名称
                        if(empty($val['houses_name'])){
                            $listData[$k]['houses_name'] = $v['houses_name'];
                        }
                        if(!in_array($v['area_id'], $val['area'])){
                            $listData[$key]['area'][$k]['id'] = $v['area_id'];
                            $listData[$key]['area'][$k]['num'] = $v['num'];
                            $listData[$key]['num'] += $v['num'];
                            if(empty($v['area_name'])){
                                $v['area_name'] = '无组团';
                            }
                            $listData[$key]['area'][$k]['area_name'] = $v['area_name'];
                            $listData[$key]['area'][$k]['diy_area_id'] = 0;
                        }
                    }
                }
            }
        }
        unset($list);
        $listData = array_values($listData);
        foreach ($listData as $k => &$v){
            $v['area'] = array_values($v['area']);
        }
        $this->return_json(['code' => 1, 'data' => $listData]);
    }
    
    /**
     * 显示楼盘
     */
    public function show_area(){
        $data = $this->data;
        //获取用户id,根据id获取用户负责的区域点位
        $user_id = decrypt($this->token)['user_id'];
        $info = $this->Madmins->get_one('diy_area_id', ['id' => $user_id]);
        $diy_area_id = (int) $info['diy_area_id'];
        if($diy_area_id == 0){
            $this->return_json(['code' => 0, 'data' => [], 'msg' => '系统还未给您分配区域']);
        }
        $houses_id = $this->input->get_post('houses_id');
        //查询用户负责的楼盘列表
        $houses = $this->Mhouses_diy_area->get_lists('houses_id, area_id', ['diy_area_id' => $diy_area_id, 'houses_id' => $houses_id]);
        if(!$houses){
            $this->return_json(['code' => 0, 'data' => [], 'msg' => '您所负责的区域暂无分配楼盘']);
        }
        //提取组团
        $area_ids = array_column($houses, 'area_id');
        if(count($area_ids) == 0){
            $this->return_json(['code' => 0, 'data' => [], 'msg' => '您所负责的区域暂无分配组团']);
        }
        $area_ids = array_unique($area_ids);
        $where['is_del'] = 0;
        $where['houses_id'] = $houses_id;
        $where['in'] = ['area_id' => $area_ids];
        $group_by = ['houses_id', 'area_id'];
        $list = $this->Mhouses_points->get_lists('houses_id, houses_name, area_id, count(id) as num,area_name', $where, ['houses_id' => 'asc'], 0, 0, $group_by);
        if(!$list){
            $this->return_json(['code' => 0, 'data' => [], 'msg' => '您所负责的组团暂无点位']);
        }
        foreach ($list as $k => $v){
            if($v['area_id'] == 0){
                $list[$k]['area_name'] = "无组团";
            }
        }
        $this->return_json(['code' => 1, 'data' => $list]);
    }
    
    /**
     * 工程人员个人区域点位接口
     */
    public function detail(){
        $data = $this->data;
        //获取用户id,根据id获取用户负责的区域点位
        $user_id = decrypt($this->token)['user_id'];
        $info = $this->Madmins->get_one('diy_area_id', ['id' => $user_id]);
        $diy_area_id = (int) $info['diy_area_id'];
        if($diy_area_id == 0){
            $this->return_json(['code' => 0, 'data' => [], 'msg' => '系统还未给您分配区域']);
        }
        $houses_id = $this->input->get_post('houses_id');

        $pageconfig = C('page.page_lists');
        $this->load->library('pagination');
        $page = (int) $this->input->get_post('page') ? : '1';
        $size = (int) $this->input->get_post('size');
        
        $where = ['is_del' => 0];
        $where['houses_id'] = $houses_id;
        //获取这个楼盘、和用户所关联的组团
        $areaInfo = $this->Mhouses_diy_area->get_lists('area_id', ['houses_id' => $houses_id, 'diy_area_id' => $diy_area_id]);
        if($areaInfo){
            $area_ids = array_column($areaInfo, 'area_id');
            if($area_ids){
                $area_ids = array_unique($area_ids);
                $where['in']['area_id'] = $area_ids;
            }
        }
        if(!$size) $size = $pageconfig['per_page'];
        
        $orderBy = ['area_id' => 'asc', 'ban' => 'asc'];
        $list = $this->Mhouses_points->get_lists("*", $where, $orderBy, $size, ($page-1)*$size);
        if(!$list){
            $this->return_json(['code' => 0, 'msg' => '暂无数据']);
        }
        //获取所有楼盘id
        $houses_ids = array_column($list, 'houses_id');
        if(count($houses_ids) > 1){
            $houses_ids = array_unique($houses_ids);
        }
        //获取所有区域id
        $area_ids = array_column($list, 'area_id');
        if(count($area_ids) > 1){
            $area_ids= array_unique($area_ids);
        }
        $houses_list = $this->Mhouses->get_lists('id, name, province, city, area', ['in' => ['id' => $houses_ids ]]);
        $area_list = $this->Mhouses_area->get_lists('id, name', ['in' => ['id' => $area_ids ]]);
        if($houses_list){
            foreach ($list as $k => &$v){
                $v['houses_name'] = '';
                $v['houses_area_name'] = '';
                foreach ($houses_list as $key => $val){
                    if($v['houses_id'] == $val['id']){
                        $v['houses_name'] = $val['name'];
                        $v['province'] = $val['province'];
                        $v['city'] = $val['city'];
                        $v['area'] = $val['area'];
                        break;
                    }
                }
            }
            unset($v);
        }
        if($area_list){
            foreach ($list as $k => &$v){
                foreach ($area_list as $key => $val){
                    if($v['area_id'] == $val['id']){
                        $v['houses_area_name'] = $val['name'];
                        break;
                    }
                }
            }
            unset($v);
        }
        $this->return_json(['code' => 1, 'data' => $list, 'page' => $page, 'time' => $this->time]);
    }
    
    /**
     * 工程人员个人区域点位接口
     */
    public function new_detail(){
        $data = $this->data;
        //获取用户id,根据id获取用户负责的区域点位
        $user_id = decrypt($this->token)['user_id'];
        $info = $this->Madmins->get_one('diy_area_id', ['id' => $user_id]);
        $diy_area_id = (int) $info['diy_area_id'];
        if($diy_area_id == 0){
            $this->return_json(['code' => 0, 'data' => [], 'msg' => '系统还未给您分配区域']);
        }
        $houses_id = $this->input->get_post('houses_id');
        $area_id = $this->input->get_post('area_id');
        
        $pageconfig = C('page.page_lists');
        $this->load->library('pagination');
        $page = (int) $this->input->get_post('page') ? : '1';
        $size = (int) $this->input->get_post('size');
        
        $where = ['is_del' => 0];
        $where['houses_id'] = $houses_id;
        $where['area_id'] = $area_id;
        
        if(!$size) $size = $pageconfig['per_page'];
        
        $orderBy = ['area_id' => 'asc', 'ban' => 'asc'];
        $list = $this->Mhouses_points->get_lists("*", $where, $orderBy, $size, ($page-1)*$size);
        if(!$list){
            $this->return_json(['code' => 0, 'msg' => '暂无数据']);
        }
        //获取所有楼盘id
        $houses_ids = array_column($list, 'houses_id');
        if(count($houses_ids) > 1){
            $houses_ids = array_unique($houses_ids);
        }
        //获取所有区域id
        $area_ids = array_column($list, 'area_id');
        if(count($area_ids) > 1){
            $area_ids= array_unique($area_ids);
        }
        $houses_list = $this->Mhouses->get_lists('id, name, province, city, area', ['in' => ['id' => $houses_ids ]]);
        $area_list = $this->Mhouses_area->get_lists('id, name', ['in' => ['id' => $area_ids ]]);
        if($houses_list){
            foreach ($list as $k => $v){
                $list[$k]['houses_name'] = '';
                $list[$k]['houses_area_name'] = '无组团';
                foreach ($houses_list as $key => $val){
                    if($v['houses_id'] == $val['id']){
                        $list[$k]['houses_name'] = $val['name'];
                        $list[$k]['province'] = $val['province'];
                        $list[$k]['city'] = $val['city'];
                        $list[$k]['area'] = $val['area'];
                        break;
                    }
                }
            }
        }
        if($area_list){
            foreach ($list as $k => $v){
                foreach ($area_list as $key => $val){
                    if($v['area_id'] == $val['id']){
                        $list[$k]['houses_area_name'] = $val['name'];
                        break;
                    }
                }
            }
        }
        //提取所有id
        $ids = array_column($list, 'id');
        $badList = $this->Mhouses_points_report->get_lists('point_id', ['in' => ['point_id' => $ids], 'repair_time' => 0]);
        if($badList){
            foreach ($list as $k => $v){
                //提取id
                $bad_point_ids = array_column($badList, 'point_id');
                if(in_array($v['id'], $bad_point_ids)){
                    $list[$k]['can_report'] = 0;
                }
            }
        }
        $this->return_json(['code' => 1, 'data' => $list, 'page' => $page, 'time' => $this->time]);
    }
    
    /**
     * 巡视按拍照更新
     */
    public function check(){
        //创建巡视记录
        $user_id = decrypt($this->token)['user_id'];
        $point_id = $this->input->get_post('point_id');
        if(!$point_id){
            $this->write_log($user_id, 1, '点位编号未传');
            $this->return_json(['code' => 0, 'msg' => '点位编号必传']);
        }
        $img_url = $this->input->get_post('img_url');
        if(!$img_url){
            $this->return_json(['code' => 0, 'msg' => '请拍照上传图片']);
        }
        $pointInfo = $this->Mhouses_points->get_one('tour_time', ['id' => $point_id]);
        if(!$pointInfo){
            $this->return_json(['code' => 0, 'msg' => '点位不存在']);
        }
        //计算已巡视的时间隔
        $trou_time = (time() - strtotime($pointInfo['tour_time']));
        if($trou_time < $this->time){
            $this->return_json(['code' => 0, 'msg' => '已巡视，请勿重复提交']);
        }
        $status = $this->input->get_post('status');
        $create_time = date('Y-m-d H:i:s');
        $add = [
            'point_id' => $point_id,
            'principal_id' => $user_id,
            'img' => $img_url,
            'status' => $status,
            'create_time' => date('Y-m-d H:i:s')
        ];
        $res = $this->Mhouses_tour_points->create($add);
        if(!$res){
            $this->write_log($user_id, 1, json_encode($add));
            $this->return_json(['code' => 0, 'msg' => '创建巡视记录失败，已记录到系统日志']);
        }
        //更新点位数据
        $up = ['tour_time' => $create_time, 'tour_id' => $res];
        $ret = $this->Mhouses_points->update_info($up, ['id' => $point_id]);
        if(!$ret){
            $this->write_log($user_id, 1, json_encode($up));
            $this->return_json(['code' => 0, 'msg' => '巡视日志已更新，点位数据未能更新']);
        }
        $this->return_json(['code' => 1, 'msg' => '操作成功']);
    }
    
    /**
     * 提交异常，更新点位状态为4（异常状态），能上画，则不跟新为4，而是提交报告
     */
    public function report(){
        $token = decrypt($this->token);
        $report_img = $this->input->get_post('report_img');
        $point_id = $this->input->get_post('point_id');
        $count = $this->Mhouses_points_report->count(['point_id' => $point_id, 'repair_time' => 0]);
        if($count){
            $operate_content = "该点位id{$point_id}已报损，请勿重复提交";
            $this->write_log($token['user_id'], 1, $operate_content);
            $this->return_json(['code' => 0, 'msg' => $operate_content]);
        }
        if(!$report_img){
            $operate_content = "点位id{$point_id}报损，请拍照上传图片";
            $this->write_log($token['user_id'], 1, $operate_content);
            $this->return_json(['code' => 0, 'msg' => $operate_content]);
        }
        $this->write_log($token['user_id'], 1, '巡视报损图片url'.$report_img);
        $report = $this->input->get_post('report');
        if(!$report) {
            $operate_content = "点位id{$point_id}报损，请选择异常选项";
            $this->write_log($token['user_id'], 1, $operate_content);
            $this->return_json(['code' => 0, 'msg' => $operate_content]);
        }
        $report_msg = $this->input->get_post('report_msg');
        $report_msg = $report_msg ? $report_msg : "";
        if($report == 14){
            if(empty($report_msg)){
                $this->return_json(['code' => 0, 'msg' => '您勾选了其他，请填写对应的说明']);
            }
        }
        
        $usable = (int) $this->input->get_post('usable');
        
        $up = [
            'report_img' => $report_img,
            'point_id' => $point_id,
            'report' => $report,
            'create_id' => $token['user_id'],
            'report_msg' => $report_msg,
            'create_time' => strtotime(date('Y-m-d')),
            'usable' => $usable
        ];
        $res = $this->Mhouses_points_report->create($up);
        if(!$res){
            $operate_content = "点位id{$point_id}报损失败";
            $this->write_log($token['user_id'], 1, $operate_content);
            $this->return_json(['code' => 0, 'msg' => $operate_content]);
        }
        //如果不能上画，则更新为4
        if($usable == 0){
            //更新点位为异常状态
            $res = $this->Mhouses_points->update_info(['point_status' => 4], ['id' => $point_id]);
            if(!$res){
                $this->write_log($token['user_id'], 1, '点位成功报异常，但未能更新点位状态,工单详情id'.$point_id);
            }
            $this->write_log($token['user_id'], 2, "app 已报损，并更新点位id:{$point_id}数据状态为4");
        }
        $this->return_json(['code' => 1, 'msg' => '提交成功']);
    }
}



