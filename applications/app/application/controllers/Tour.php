<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
 * @author yonghua
 * desc:巡视
 * 254274509@qq.com
 */

class Tour extends MY_Controller {
    private $token;
    private $time = 14*24*3600;
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
            'Model_houses_diy_area' => 'Mhouses_diy_area'
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
        $list = $this->Mhouses_points->get_lists('houses_id, houses_name, area_id, count(id) as num,area_name', $where, ['houses_id' => 'asc'], 0, 0, $group_by);
        //提取楼盘ids
        $listData = [];
        if($list){
            foreach ($houses_ids as $k => $v){
                $listData[$k]['houses_id'] = $v;
                $listData[$k]['areas'] = '';
                $listData[$k]['houses_name'] = '';
                $listData[$k]['area'] = [];
            }
            //行政区域
            $areaList = $this->Mhouses->get_lists('id,city,area', ['in' => ['id' => $houses_ids]]);
            if($areaList){
                foreach ($listData as $k => $v){
                    foreach ($areaList as $key => $val){
                        if($v['houses_id'] == $val['id']){
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
                        if(!in_array($v['area_id'], $val['area_id'])){
                            $listData[$key]['area'][$k]['id'] = $v['area_id'];
                            $listData[$key]['area'][$k]['num'] = $v['num'];
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
        
        $where = ['is_del' => 0, 'diy_area_id' => $diy_area_id];
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
        }
        $this->return_json(['code' => 1, 'data' => $list, 'page' => $page, 'time' => $this->time]);
    }
    
    /**
     * 巡视更新
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
}



