<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
 * @author yonghau
 * 254274509@qq.com
 */
class Point extends MY_Controller {
    
    private $token;
    
    public function __construct() {
        parent::__construct();
        
        $this->token = trim($this->input->get_post('token'));
        $this->doCheckToken($this->token);
        
        $this->load->model([
            'Model_token' => 'Mtoken',
            'Model_houses_points' => 'Mhouses_points',
            'Model_houses_area' => 'Mhouses_area',
            'Model_houses' => 'Mhouses',
            'Model_houses_points_report' => 'Mhouses_points_report',
            'Model_houses_tour_points' => 'Mhouses_tour_points'
        ]);
    }
    
    /**
     * 点位列表接口
     */
    public function index(){
        $data = $this->data;
        $pageconfig = C('page.page_lists');
        $this->load->library('pagination');
        $page = (int) $this->input->get_post('page') ? : '1';
        $size = (int) $this->input->get_post('size');
        $code = trim( $this->input->get_post('code') );
        
        $where = ['is_del' => 0];
        if(!empty($code)) $where['like'] = ['code'=> $code];
        if(!$size) $size = $pageconfig['per_page'];
        
        $orderBy = ['houses_id' => 'asc'];
        $list = $this->Mhouses_points->get_lists("id,code,houses_id,area_id,ban,unit,floor,addr,point_status", $where, $orderBy, $size, ($page-1)*$size);
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
                $v['can_report'] = 1;
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
                $v['houses_area_name'] = '';
                foreach ($area_list as $key => $val){
                    if($v['area_id'] == $val['id']){
                        $v['houses_area_name'] = $val['name'];
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
        $this->return_json(['code' => 1, 'data' => $list, 'page' => $page]);
    }
    
    /**
     * 巡视点位
     */
    public function tourPoint(){
        $point_id = (int) $this->input->get_post('point_id');
        $status = (int) $this->input->get_post('status');
        $principal_id = (int) $this->input->get_post('principal_id');
        $img_url = trim($this->input->get_post('img_url'));
        $create_time = date('Y-m-d H:i:s');
        if(empty($img_url)) $this->return_json(['code' => 0, 'msg' => '请上传图片']);
        $up = [
            'point_id' => $point_id,
            'principal_id' => $principal_id,
            'img' => $img_url,
            'status' => $status,
            'create_time' => $create_time
        ];
        $res = $this->Mhouses_tour_points->create($up);
        if(!$res) $this->return_json(['code' => 0, 'msg' => '提交失败']);
        $this->Mhouses_points->update_info(['tour_time' => $create_time], ['id' => $point_id]);
        $this->return_json(['code' => 0, 'msg' => '操作成功']);
    }
}