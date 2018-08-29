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
            $this->return_json(['code'=> 0, 'msg' => "添加成功"]);
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
