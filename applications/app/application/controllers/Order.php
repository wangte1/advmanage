<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
 * @author tete
 * desc:广告定单相关信息
 * 1517796462@qq.com
 */

class Order extends MY_Controller {
    private $token;
    public function __construct() {
        parent::__construct();
        $this->token = trim($this->input->get_post('token'));
        $this->doCheckToken($this->token);
        $this->load->model([
            'Model_houses_orders' => 'Mhouses_orders',
            'Model_houses_customers' => 'Mhouses_customers'
        ]);
    }

    /**
     * 广告画面相关信息
     */
    public  function index(){
        $pageconfig = C('page.page_lists');
        $this->load->library('pagination');
        $page = (int) $this->input->get_post('page') ? : '1';
        $size = (int) $this->input->get_post('size');
        if(!$size) $size = $pageconfig['per_page'];
        $where = ['order_status'=> 1,'is_del'=>0 ,'pid'=> 0];
        $orderBy = ['id' => 'asc'];
        $order_list = $this->Mhouses_orders->get_lists("order_code,order_type,release_start_time,release_end_time,customer_id,point_ids",$where, $orderBy, $size, ($page-1)*$size );
        $customer_list = $this->Mhouses_customers->get_lists("id,name",['is_del'=>0]);
        if($order_list&&$customer_list){
            foreach ($order_list as $k=>$v){
                $order_list[$k]['customer_name'] = '';
                $order_list[$k]['num'] = 0;
                $order_list[$k]['order_type_text'] = "冷光订单";
                if($v['order_type'] == 2){
                    $order_list[$k]['order_type_text'] = "广告机订单";
                }
                if(!empty($v['point_ids'])){
                    $tmp = explode(',', $v['point_ids']);
                    if(is_array($tmp)){
                        $order_list[$k]['num'] = count(array_unique($tmp));
                    }
                }
                unset($order_list[$k]['point_ids']);
                foreach ($customer_list as $key => $val){
                    if($val['id'] == $v[customer_id]){
                        $order_list[$k]['customer_name'] = $val['name'];
                    }
                }
            }
             $this->return_json(['code' => 1, 'order_list' =>  $order_list, 'msg' => "ok",'page'=>$page]);
        }
             $this->return_json(['code' => 0, 'order_list' => [], 'msg' => "null"]);
    }
    
    /**
     * 更新图片地址
     */
    public function updateImage(){
        $id = (int) $this->input->get_post('id');
        $adv_img =$this->input->get_post('adv_img');
        if($id&&$adv_img){
            $where = ['id' => $id];
            $count = $this->Mhouses_orders->count($where);
            if($count){
                $res = $this->Mhouses_orders->update_info(['adv_img' => $adv_img], $where);
                if($res){
                    $this->return_json(['code' => 1, 'msg' => "操作成功"]);
                }
            }
        }
        $this->return_json(['code' => 0, 'msg' => "操作失败"]);
    }
    
}