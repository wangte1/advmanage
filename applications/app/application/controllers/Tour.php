<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
 * @author yonghua
 * desc:巡视
 * 254274509@qq.com
 */

class Tour extends MY_Controller {
    private $token;
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
        ]);
    }
    
    /**
     * 工程人员个人区域点位接口
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
        $pageconfig = C('page.page_lists');
        $this->load->library('pagination');
        $page = (int) $this->input->get_post('page') ? : '1';
        $size = (int) $this->input->get_post('size');
        
        $where = ['is_del' => 0, 'in' => ['diy_area_id' => $diy_area_id]];
        if(!$size) $size = $pageconfig['per_page'];
        
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
        $this->return_json(['code' => 1, 'data' => $list, 'page' => $page]);
    }
    
    /**
     * 巡视更新
     */
    public function check(){
        //创建巡视记录
        $point_id = $this->input->get_post('point_id');
        $img_url = $this->input->get_post('img_url');
        $status = $this->input->get_post('status');
        $user_id = decrypt($this->token)['user_id'];
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
        $this->Mhouses_points->update_info($up, ['id' => $point_id]);
        $this->return_json(['code' => 1, 'msg' => '操作成功']);
    }
}



