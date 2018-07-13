<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
 * @author yonghau
 * desc:后台登陆
 * 254274509@qq.com
 */

class Customers extends MY_Controller {
    private $token;
    public function __construct() {
        parent::__construct();
        
        $this->token = trim($this->input->get_post('token'));
        $this->doCheckToken($this->token);
        
        $this->load->model([
            'Model_houses_customers' => 'Mhouses_customers'
        ]);
    }
    
    public function index(){
        $data = $this->data;
        $page =  intval($this->input->get("per_page",true)) ?  : 1;
        $size = $this->pageconfig['per_page'];
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
}