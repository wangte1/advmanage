<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
 * @author yangxiong
 * 867332352@qq.com
 */
class Task extends MY_Controller {
    
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
        	'Model_houses_assign' => 'Mhouses_assign',
        	'Model_houses_orders' => 'Mhouses_orders',
        ]);
    }
    
    /**
     * 派工任务列表接口
     */
    public function index(){
        $data = $this->data;
        $pageconfig = C('page.page_lists');
        $this->load->library('pagination');
        $page = (int) $this->input->get_post('page') ? : '1';
        $size = (int) $this->input->get_post('size');
        $status = (int) $this->input->get_post('status');
        
        $where = ['A.is_del' => 0];
        if(!$size) $size = $pageconfig['per_page'];
        if($status == 3) {
        	$where['A.status'] = $status;
        }else {
        	$where['A.status<>'] = 3;
        }
        
        $token = decrypt($this->input->get_post('token'));
        $where['A.charge_user'] = $token['user_id'];
        
        $list = $this->Mhouses_assign->get_join_lists($where,['A.id'=>'desc'],$size,($page-1)*$size);
 
        $this->return_json(['code' => 1, 'data' => json_encode($list), 'page' => $page]);
    }
}