<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
 * @author yonghau
 * desc:意向单
 * 254274509@qq.com
 */

class Intention extends MY_Controller {
    private $token;
    public function __construct() {
        parent::__construct();
        
        $this->token = trim($this->input->get_post('token'));
        $this->doCheckToken($this->token);
        
        $this->load->model([
            'Model_houses_want_orders' => 'Mhouses_want_orders',
            'Model_houses_points' => 'Mhouses_points',
            'Model_houses_customers' => 'Mhouses_customers'
        ]);
    }
    
    public function index(){
        $data = $this->data;
        $pageconfig = C('page.page_lists');
        $this->load->library('pagination');
        $page = (int) $this->input->get_post("page");
        if(!$page) $page = 1;
        $size = (int) $this->input->get_post("size");
        if(!$size) $size = $pageconfig['per_page'];
        $where = ['is_del' => 0, 'status' => "1"];
        $field = "id, customer_id, points_count, order_type";
        $order_by = ['create_time' => 'desc'];
        $list = $this->Mhouses_want_orders->get_lists($field, $where, $order_by, $size, ($page-1)*$size);
        if(!$list){
            $this->return_json(['code'=> 0, 'msg' => "暂无数据"]);
        }
        //提取客户id
        $customer_ids = array_column($list, 'customer_id');
        $customer_list = $this->Mhouses_customers->get_lists(['id, name'], ['in' => ['id' => $customer_ids]]);
        foreach ($list as $k => $v){
            $list[$k]['customer_name'] = "";
            $list[$k]['order_type'] = "冷光灯箱";
            if($v['order_type'] == 2){
                $list[$k]['order_type'] = "广告机";
            }
            foreach ($customer_list as $key => $val){
                if($v['customer_id'] == $val['id']){
                    $list[$k]['customer_name'] = $val['name'];
                }
            }
        }
        $this->return_json(['code' => 1, 'data'=> $list, 'msg' => "ok"]);
    }
    
    /**
     * 审核意向单
     */
    public function check(){
        $id = (int) $this->input->get_post('id');
        $status = (int) $this->input->get_post('status');
        $check_remark = $this->input->get_post('check_remark');
        $count = $this->Mhouses_want_orders->count(['id' => $id, 'is_del' => 0, 'status' => 1]);
        if(!$count){
            $this->return_json(['code' => 0, 'msg' => "数据不存在"]);
        }
        $res = $this->Mhouses_want_orders->update_info(['status' => $status, 'check_remark'=> $check_remark], ['id' => $id, 'is_del' => 0]);
        if(!$res){
            $this->return_json(['code' => 0, 'msg' => "操作失败"]);
        }
        $this->return_json(['code' => 1, 'msg' => "执行成功"]);
    }
    
    /**
     * 添加意向单
     */
    public function add(){
        if(IS_POST){
            $post_data = $this->input->post();
        }else{
            $post_data = $this->input->get();
        }
        $token = decrypt($this->token);
        if($post_data['customer_id'] <= 0) {
            $this->return_json(['code'=> 0, 'msg' => "请选择客户"]);
        }
        $points_count = (int) $post_data['points_count'];
        if(!$points_count) {
            $this->return_json(['code'=> 0, 'msg' => "请填写点位数"]);
        }
        $post_data['create_user'] = $token['user_id'];
        $post_data['create_time'] = date('Y-m-d H:i:s');
        unset($post_data['token']);
        $id = $this->Mhouses_want_orders->create($post_data);
        if ($id) {
            $this->write_log($token['user_id'], 1, "app 新增意向订单,订单id【".$id."】");
            $this->return_json(['code'=> 1, 'msg' => "添加成功"]);
        } else {
            $this->return_json(['code'=> 0, 'msg' => "添加失败"]);
        }
    }
    
    public function getConfig(){
        $data = [
            'customer' => [],
            'houses_type' => C('public.houses_type'),
            'put_type' => C('housespoint.put_trade'),
            'point_type' => [
                1 => "冷光灯箱",
                2 => "广告机"
            ]
        ];
        $data['customer'] = $this->Mhouses_customers->get_lists(['id, name'], ['is_del' => 0]);
        foreach ($data as $k => &$v){
            if($k != "customer"){
                $v = getConfig($v);
            }
        }
        unset($v);
        $this->return_json(['code' => 1, 'data'=> $data, 'msg' => "ok"]);
    }
}
