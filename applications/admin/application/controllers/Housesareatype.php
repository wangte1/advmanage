<?php
/**
 * 楼盘组团置业类型管理控制器
 * 867332352@qq.com
 */
defined('BASEPATH') or exit('No direct script access allowed');
class Housesareatype extends MY_Controller{

    public function __construct(){
        parent::__construct();
        $this->load->model([
        	'Model_houses' => 'Mhouses',
            'Model_houses_area' => 'Mhouses_area',
        	'Model_houses_points' => 'Mhouses_points',
        	'Model_houses_group' => 'Mhouses_group',
            'Model_houses_area_type' => 'Mhouses_area_type'
         ]);
        $this->data['code'] = 'community_manage';
        $this->data['active'] = 'houses_area_type_list';
        $this->data['area_grade'] = C("public.area_grade");
    }

    /*
    * 列表
    * 867332352@qq.com
    */
    public function index(){
        $data = $this->data;
        //提取楼盘、组团
        $where = ['is_del' => 0];
        $diy_area_id = (int) $this->input->get('diy_area_id');
        if($diy_area_id){
            $where['diy_area_id'] = $diy_area_id;
            $data['diy_area_id'] = $diy_area_id;
        }
        $group_by = ['houses_id', 'area_id'];
        $list = $this->Mhouses_points->get_lists('houses_id, houses_name, area_id, houses_type, count(id) as num,area_name', $where, ['houses_id' => 'asc'], 0, 0, $group_by);
        //提取楼盘ids
        $listData = [];
        if($list){
            $houses_ids = array_column($list, 'houses_id');
            if($houses_ids){
                $houses_ids = array_unique($houses_ids);
                foreach ($houses_ids as $k => $v){
                    $listData[$k]['houses_id'] = $v;
                    $listData[$k]['houses_name'] = '';
                    $listData[$k]['area'] = [];
                }
            }
            //组团
            $housesAreaList = $this->Mhouses_area->get_lists('id,name', ['is_del' => 0]);
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
                            if(empty($v['area_name'])){
                                $v['area_name'] = '无组团';
                            }
                            $listData[$key]['area'][$k]['area_name'] = $v['area_name'];
                            $listData[$key]['area'][$k]['houses_type_id'] = 0;
                            $listData[$key]['area'][$k]['houses_type'] = '';
                        }
                    }
                }
            }
        }
        unset($list);
        $houses_area_type = $this->Mhouses_area_type->get_lists();
        foreach ($listData as $k => $v){
            foreach ($houses_area_type as $k1 => $v1){
//                 var_dump($v['houses_id'],$v1['houses_id'],$listData[$k]['area'][$k1]['id'],$v1['area_id']);exit;
                if($v['houses_id'] == $v1['houses_id'] && $listData[$k]['area'][$k1]['id'] == $v1['area_id']){
                    $listData[$k]['area'][$k1]['houses_type_id'] = $v1['houses_type'];
                }
            }
        }
        //为解决数据不同步问题
        if($housesAreaList){
            foreach ($listData as $k => $v){
                if(count($v['area'])){
                    foreach ($v['area'] as $key => $val){
                        foreach ($housesAreaList as $keys => $vals){
                            if($val['id'] == $vals['id']){
                                $listData[$k]['area'][$key]['area_name'] = $vals['name'];
                                break;
                            }
                        }
                    }
                }
            }
        }
        $data['list'] = $listData;
        $this->load->view("housesareatype/index",$data);
    }
    /**
     * 批量更新楼盘组团置业类型
     */
    public function set_houses_area_type(){
        if(IS_POST){
            $houses_type = $this->input->post('houses_type');
            $houses_id = $this->input->post('houses_id');
            $area_id = $this->input->post('area_id');
            $count_houses = $this->Mhouses_area_type->count(['houses_id' => $houses_id, 'area_id' => $area_id]);
            if($count_houses){
                $res = $this->Mhouses_area_type->update_info(['houses_type' => $houses_type, 'create_time' => date('Y-m-d H:i:s')], ['houses_id' => $houses_id, 'area_id' => $area_id]);
            }else{
                $add = [
                    'houses_id' => $houses_id,
                    'area_id' => $area_id,
                    'houses_type' => $houses_type,
                    'create_time' => date('Y-m-d H:i:s')
                ];
                $res = $this->Mhouses_area_type->create($add);
            }
            if(!$res){
                $this->return_json(['code' => 0, 'msg' => '操作失败']);
            }
            $count = $this->Mhouses_points->count(['houses_id' => $houses_id, 'area_id' => $area_id]);
            if($count){
                //批量更新点位
                $res = $this->Mhouses_points->update_info(['houses_type' => $houses_type],['houses_id' => $houses_id, 'area_id' => $area_id]);
            }
            if(!$res){
                $this->return_json(['code' => 0, 'msg' => '点位更新失败']);
            }
            $this->return_json(['code' => 1, 'msg' => '编辑成功']);
        }
    }
}