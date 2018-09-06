<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
 * @author yonghau
 * desc:预定单
 * 254274509@qq.com
 */

class PreOrder extends MY_Controller {
    private $token;
    public function __construct() {
        parent::__construct();
        
        $this->token = trim($this->input->get_post('token'));
        $this->doCheckToken($this->token);
        
        $this->load->model([
            'Model_houses_scheduled_orders' => 'Mhouses_scheduled_orders',
            'Model_houses_points' => 'Mhouses_points',
            'Model_houses_customers' => 'Mhouses_customers',
            'Model_houses_area' => 'Mhouses_area',
            'Model_houses' => 'Mhouses',
        ]);
    }
    
    /**
     * 审核意向单列表点位
     */
    public function index(){
        $data = $this->data;
        $pageconfig = C('page.page_lists');
        $this->load->library('pagination');
        $page = (int) $this->input->get_post("page");
        if(!$page) $page = 1;
        $size = (int) $this->input->get_post("size");
        if(!$size) $size = $pageconfig['per_page'];
        $where = ['is_del' => 0, 'order_status' => "1", 'bm_agree' => 0, 'is_confirm'=> 1];//业务主管未审批的
        $field = "id,lock_customer_id,point_ids,confirm_point_ids,order_type,lock_start_time,lock_end_time, schedule_start, schedule_end,confirm_img";
        $order_by = ['create_time' => 'desc'];
        $list = $this->Mhouses_scheduled_orders->get_lists($field, $where, $order_by, $size, ($page-1)*$size);
        if(!$list){
            $this->return_json(['code'=> 0, 'msg' => "暂无数据"]);
        }
        //提取客户id
        $customer_ids = array_column($list, 'lock_customer_id');
        $customer_list = $this->Mhouses_customers->get_lists(['id, name'], ['in' => ['id' => $customer_ids]]);
        foreach ($list as $k => $v){
            $list[$k]['customer_name'] = "";
            $list[$k]['order_type'] = "冷光灯箱";
            $list[$k]['lock_num'] = 0;
            $list[$k]['confirm_num'] = 0;
            if($v['point_ids']){
                $tmp = explode(',', $v['point_ids']);
                $list[$k]['lock_num'] = count($tmp);
            }
            if($v['confirm_point_ids']){
                $tmp = explode(',', $v['confirm_point_ids']);
                $list[$k]['confirm_num'] = count($tmp);
            }
            unset($list[$k]['confirm_point_ids'], $list[$k]['point_ids']);
            if($v['order_type'] == 2){
                $list[$k]['order_type'] = "广告机";
            }
            foreach ($customer_list as $key => $val){
                if($v['lock_customer_id'] == $val['id']){
                    $list[$k]['customer_name'] = $val['name'];
                }
            }
            $data[$k]['customer_id'] = $v['lock_customer_id'];
            unset($list[$k]['lock_customer_id']);
        }
        $this->return_json(['code' => 1, 'data'=> $list, 'msg' => "ok"]);
    }
    
    /**
     * 业务主管查看预订单点位列表详情
     */
    public function bm_detail(){
        $id = (int) $this->input->get_post('id');
        $where = ['is_confirm'=> 1, 'order_status' => 1, 'is_del' => 0, 'bm_agree' => 0];
        $info = $this->Mhouses_scheduled_orders->get_one('confirm_point_ids', $where);
        if(!$info || empty($info['confirm_point_ids'])){
            $this->return_json(['code' => 0, 'data'=> [], 'msg' => "暂无数据"]);
        }
        $point_ids = explode(',', $info['confirm_point_ids']);
        $where = ['in' => ['id' => $point_ids]];
        $orderBy = ['houses_id' => 'asc'];
        $list = $this->Mhouses_points->get_lists("id,code,houses_id,area_id,ban,unit,floor,addr", $where, $orderBy);
        if($list){
            foreach ($list as $k => $v){
                switch ($v['addr']){
                    case 1 :
                        $list[$k]['addr'] = "门禁";
                    case 2 :
                        $list[$k]['addr'] = "地上电梯前室";
                    case 3 :
                        $list[$k]['addr'] = "地下电梯前室";
                }
            }
            //提取楼盘id和组团id
            $houses_id = array_column($list, 'houses_id');
            $area_id = array_column($list, 'area_id');
            $houses_List = $this->Mhouses->get_lists('id, name', ['in' => ['id' => $houses_id]]);
            $houses_area_List = $this->Mhouses_area->get_lists('id, name', ['in' => ['id' => $area_id]]);
            if($houses_List){
                foreach ($list as $k => $v){
                    $list[$k]['houses_name'] = "";
                    foreach ($houses_List as $key => $val){
                        if($v['houses_id'] == $val['id']){
                            $list[$k]['houses_name'] = $val['name'];
                        }
                    }
                }
            }
            if($houses_area_List){
                foreach ($list as $k => $v){
                    $list[$k]['houses_area_name'] = "";
                    foreach ($houses_List as $key => $val){
                        if($v['area_id'] == $val['id']){
                            $list[$k]['houses_area_name'] = $val['name'];
                        }
                    }
                }
            }
            $this->return_json(['code' => 1, 'data' => $list, 'msg' => "ok"]);
        }
        $this->return_json(['code' => 0, 'data'=> [], 'msg' => "暂无数据"]);
    }
    
    /**
     * 业务主管同意审核
     */
    public function bm_agree(){
        $id = (int) $this->input->get_post('id');
        $where = ['id' => $id, 'bm_agree' => 0];
        $count = $this->Mhouses_scheduled_orders->count($where);
        if($count){
            $res = $this->Mhouses_scheduled_orders->update_info(['bm_agree' => 1], $where);
            if($res){
                $this->return_json(['code' => 1, 'msg' => "操作成功"]);
            }
        }
        $this->return_json(['code' => 0, 'msg' => "操作失败"]);
    }
    
    /**
     * 媒介主管同意审核
     */
    public function mm_agree(){
        $id = (int) $this->input->get_post('id');
        $where = ['id' => $id, 'mm_agree' => 0];
        $count = $this->Mhouses_scheduled_orders->count($where);
        if($count){
            $res = $this->Mhouses_scheduled_orders->update_info(['mm_agree' => 1], $where);
            if($res){
                $this->return_json(['code' => 1, 'msg' => "操作成功"]);
            }
        }
        $this->return_json(['code' => 0, 'msg' => "操作失败"]);
    }
}