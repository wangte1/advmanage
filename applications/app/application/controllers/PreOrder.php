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
            'Model_houses_customers' => 'Mhouses_customers'
        ]);
    }
    
    /**
     * 审核点位
     */
    public function index(){
        $data = $this->data;
        $pageconfig = C('page.page_lists');
        $this->load->library('pagination');
        $page = (int) $this->input->get_post("page");
        if(!$page) $page = 1;
        $size = (int) $this->input->get_post("size");
        if(!$size) $size = $pageconfig['per_page'];
        $where = ['is_del' => 0, 'order_status' => "1", 'bm_agree' => 0];//业务主管未审批的
        $field = "id,lock_customer_id,point_ids,confirm_point_ids,order_type,lock_start_time,lock_end_time, schedule_start, schedule_end";
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
}