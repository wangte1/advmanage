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
            'Model_houses' => 'Mhouses'
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
        if(!$size) {
            $size = $pageconfig['per_page'];
        }
        $where = ['is_del' => 0];
        $orderBy = ['houses_id' => 'asc'];
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
        $this->return_json(['code' => 1, 'data' => json_encode($list), 'page' => $page]);
    }
}