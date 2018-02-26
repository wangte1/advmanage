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
        	'Model_houses_order_inspect_images' => 'Mhouses_order_inspect_images',
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
        if($status == 4) {
        	$where['A.status'] = $status;
        }else {
        	$where['A.status<>'] = 4;
        }
        
        $token = decrypt($this->input->get_post('token'));
        $where['A.charge_user'] = $token['user_id'];
        
        $list = $this->Mhouses_assign->get_join_lists($where,['A.id'=>'desc'],$size,($page-1)*$size);
 
        $this->return_json(['code' => 1, 'data' => json_encode($list), 'page' => $page]);
    }
    
    /**
     * 根据任务id获取任务信息
     */
    public function get_info() {
    	$assignId = (int) $this->input->get_post('assignId');
    	$where['A.id'] = $assignId;
    	$list = $this->Mhouses_assign->get_join_lists($where);
    	$this->return_json(['code' => 1, 'data' => json_encode($list)]);
    }
    
    /**
     * 确认任务
     */
    public function confirm() {
    	$assignId = (int) $this->input->get_post('assignId');
    	$res = $this->Mhouses_assign->update_info(['status' => 3], ['id' => $assignId]);
    	if(!$res){
    		$this->return_json(['code' => 1, 'msg' => '确认成功!']);
    	}
    	
    	$this->return_json(['code' => 0, 'msg' => '确认失败，请联系管理员!']);
    }
    
    /**
     * 获取任务中的点位详情
     */
    public function get_point_list() {
    	
    	$pageconfig = C('page.page_lists');
    	$this->load->library('pagination');
    	$page = (int) $this->input->get_post('page') ? : '1';
    	$size = (int) $this->input->get_post('size');
    	
    	if(!$size) $size = $pageconfig['per_page'];
    	
    	$assignId = (int) $this->input->get_post('assignId');
    	$where['id'] = $assignId;
    	$assign_list = $this->Mhouses_assign->get_one('id, order_id, houses_id, ban', $where);
    	$order_list = $this->Mhouses_orders->get_one('id, point_ids', ['id' => $assign_list['order_id']]);
    	
    	$where_point['in']['A.id'] = explode(',', $order_list['point_ids']);
    	$where_point['A.houses_id'] = $assign_list['houses_id'];
    	if($assign_list['ban']) {
    		$where_point['A.ban'] = $assign_list['ban'];
    	}
    	$points = $this->Mhouses_points->get_points_lists($where_point,[],$size,($page-1)*$size);
    	
    	//根据点位id获取对应的图片
    	$data['images'] = "";
    	if(count($points) > 0) {
    		$where['in'] = array("point_id"=>array_column($points,"id"));
    		$where['order_id'] = $assign_list['order_id'];
    		$where['assign_id'] = $assignId;
    		$where['assign_type'] = 1;
    		$where['type'] = 1;
    		$data['images'] = $this->Mhouses_order_inspect_images->get_lists("*",$where);
    	}
    	
    	$list = array();
    	foreach ($points as $key => $val) {
    		$val['image'] = array();
    		if($data['images']){
    			foreach($data['images'] as $k=>$v){
    				if($val['id'] == $v['point_id']){
    					$val['image'][] = $v;
    				}
    			}
    		}
    		$list[] = $val;
    	}
    	
    	$this->return_json(['code' => 1, 'data' => json_encode($list), 'page' => $page]);
    }
}