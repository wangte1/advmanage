<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
 * @author yonghau
 * desc:后台登陆
 * 254274509@qq.com
 */

class Customer extends MY_Controller {
    private $token;
    public function __construct() {
        parent::__construct();
        
        $this->token = trim($this->input->get_post('token'));
        $this->doCheckToken($this->token);
        
        $this->load->model([
            'Model_admins' => 'Madmins',
            'Model_houses_customers' => 'Mhouses_customers',
            'Model_houses_customers_linkman' => 'Mhouses_customers_linkman',
            'Model_houses_customers_linkman_log' => 'Mhouses_customers_linkman_log',
            'Model_houses_scheduled_orders' => 'Mhouses_scheduled_orders'
        ]);
    }
    
    public function index(){
        $data = $this->data;
        $pageconfig = C('page.page_lists');
        $this->load->library('pagination');
        $page = (int) $this->input->get_post('page') ? : '1';
        $size = (int) $this->input->get_post('size');
        if(!$size) $size = $pageconfig['per_page'];
        
        $where['is_del'] = 0;
        
        $data['name'] = trim($this->input->get('name'));
        if ($this->input->get('name')) {
            $where['like']['name'] = $data['name'];
        }
        
        //类型
        $data['type'] = $this->input->get('type');
        if($this->input->get('type')){
            $where['type'] = $data['type'];
        }
        
        $list = $this->Mhouses_customers->get_lists('*', $where,array("id"=>"desc"), $size, ($page-1)*$size );
        if(!$list) $this->return_json(['code' => 0, 'list' => [], 'msg' => "暂无数据"]);
        $type = C('public.houses_customer_type');
        foreach ($list as $k => $v){
            $list[$k]['type_desc'] = '';
            foreach ($type as $key => $val){
                if($v['type'] == $key){
                    $list[$k]['type_desc'] = $val;
                }
            }
        }
        $this->return_json(['code' => 1, 'list' => $list, 'msg' => "ok"]);
    }
    
    /**
     * 新版客户列表
     */
    public function new_index(){
        $data = $this->data;
        $pageconfig = C('page.page_lists');
        $this->load->library('pagination');
        $page = (int) $this->input->get_post('page') ? : '1';
        $size = (int) $this->input->get_post('size');
        if(!$size) $size = $pageconfig['per_page'];
        
        $where = ['is_del' => 0, 'is_self' => 0];
        $name = trim($this->input->get_post('name'));
        if($name) $where['like']['name'] = $name;
        $fields = "id,name,addr,remarks,creator,customer_type,salesman_id,is_check";
        $list = $this->Mhouses_customers->get_lists($fields , $where, [], $size, ($page-1)*$size);
        if(!$list) $this->return_json(['code' => 0, 'msg' => "暂无数据"]);
        //提取业务员ids
        $salesman_ids = array_column($list, 'salesman_id');
        $salesman_list = $this->Madmins->get_lists('id, fullname', ['in' => ['id' => $salesman_ids]]);
        //数据初始化
        foreach ($list as $k => $v){
            $list[$k]['salesman_name'] = '';
            $list[$k]['pre_order_num'] = 0;
            $list[$k]['linkman_num'] = 0;
            $list[$k]['follow_num'] = 0;
            $list[$k]['last_follow_time'] ='0000-00-00';
        }
        if($salesman_list){
            foreach ($list as $k => $v){
                foreach ($salesman_list as $key => $val){
                    if($v['salesman_id'] == $val['id']){
                        $list[$k]['salesman_name'] = $val['fullname'];break;
                    }
                }
            }
        }
        $this->return_json(['code' => 1, 'data' => $list, 'msg' => 'ok', 'page' => $page]);
    }
    
    /**
     * 返回客户类型配置文件
     */
    public function getConfig(){
        $config = C('public.houses_customer_type');
        if($config){
            $data = [];
            foreach ($config as $k => $v){
                $arr = ['index' => $k, 'reason' => $v];
                $data['type'][] = $arr;
            }
            $enterprise_type = [
                '1' => '私企',
                '2' => '国企',
                '3' => '央企',
            ];
            foreach ($enterprise_type as $k => $v){
                $arr = ['index' => $k, 'reason' => $v];
                $data['enterprise_type'][] = $arr;
            }
            $customer_type = [
                '1' => '普通用户',
                '2' => '重点用户',
            ];
            foreach ($customer_type as $k => $v){
                $arr = ['index' => $k, 'reason' => $v];
                $data['customer_type'][] = $arr;
            }
            $this->return_json(['code' => 1, 'data' => $data, "msg" => "ok"]);
        }
        $this->return_json(['code' => 0, 'data' => [], "msg" => "无法读取配置文件"]);
    }
    
    /**
     * 添加客户接口
     */
    public function addCustomer(){
        if(IS_POST){
            $post = $this->input->post();
        }else{
            $post = $this->input->post();
        }
        $token = decrypt($this->token);
        if(!isset($post['name'])){
            $this->return_json(['code' => 0, 'msg' => '客户名称必填']);
        }
        if(empty($post['name'])){
            $this->return_json(['code' => 0, 'msg' => '客户名称不能为空']);
        }
        $post['creator'] = $post['salesman_id'] = $token['user_id'];
        $post['create_time'] = date('Y-m-d H:i:s');
        unset($post['token']);
        if(!isset($post['remarks'])){
            $post['remarks'] = "";
        }
        $res = $this->Mhouses_customers->create($post);
        if(!$res) $this->return_json(['code' => 0, 'msg' => '添加失败']);
        $this->return_json(['code' => 1, 'msg' => '操作成功']);
    }
    
    /**
     * 预定单列表
     */
    public function preOrder(){
        $customer_id = (int) $this->input->get_post('customer_id');
        $fields = "id, order_type, is_confirm, lock_start_time , lock_end_time, point_ids";
        $where = ['is_del' => 0, 'lock_customer_id' => $customer_id];
        $where['in']['order_status'] = [1, 2];
        $order_by = ['create_time' => 'asc'];
        $list = $this->Mhouses_scheduled_orders->get_lists($fields, $where, $order_by);
        if(!$list){
            $this->return_json(['code' => 0, 'data' => [], 'msg' => "暂无数据"]);
        }
        foreach ($list as $k => $v){
            $list[$k]['order_type_text'] = "冷光订单";
            if($v['order_type'] == 2){
                $list[$k]['order_type_text'] = "广告机订单";
            }
            $list[$k]['lock_num'] = 0;
            if(!empty($v['point_ids'])){
                $tmp = explode(',', $v['point_ids']);
                if(is_array($tmp)){
                    $list[$k]['lock_num'] = count(array_unique($tmp));
                }
            }
        }
        $this->return_json(['code' => 1, 'data' => $list, 'msg' => "ok"]);
    }
}