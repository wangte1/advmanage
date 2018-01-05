<?php 
/**
* 订单管理控制器
* @author 867332352@qq.com
*/
defined('BASEPATH') or exit('No direct script access allowed');
class Housesconfirm extends MY_Controller{

    public function __construct(){
        parent::__construct();
        $this->load->model([
        	'Model_houses_assign' => 'Mhouses_assign',
        	'Model_houses' => 'Mhouses',
        	'Model_houses_orders' => 'Mhouses_orders',
        	'Model_admins' => 'Madmins',
        	'Model_houses_customers' => 'Mhouses_customers',
        	'Model_houses_points' => 'Mhouses_points',
        	'Model_houses_order_inspect_images' => 'Mhouses_order_inspect_images'
        		
        ]);
        $this->data['code'] = 'horders_manage';
        $this->data['active'] = 'houses_confirm_list';

        $this->data['order_type_text'] = C('housesorder.houses_order_type'); //订单类型
        $this->data['houses_assign_status'] = C('housesorder.houses_assign_status'); //派单状态
        $this->data['point_addr'] = C('housespoint.point_addr');	//点位位置
    }
    

    /**
     * 订单列表
     */
    public function index() {
        $data = $this->data;
        $pageconfig = C('page.page_lists');
        $this->load->library('pagination');
        $page =  intval($this->input->get("per_page",true)) ?  : 1;
        $size = $pageconfig['per_page'];
		
        $where = [];
        if($data['userInfo']['group_id'] != 1 && $data['userInfo']['group_id'] != 5) {
        	$where['A.charge_user'] = $data['userInfo']['id'];
        }
        if ($this->input->get('province')) $where['like']['B.province'] = $this->input->get('province');
        if ($this->input->get('city')) $where['like']['B.city'] = $this->input->get('city');
        if ($this->input->get('area')) $where['like']['B.area'] = $this->input->get('area');
        if ($this->input->get('houses_name')) $where['like']['B.name'] = $this->input->get('houses_name');
        if ($this->input->get('customer_name')) $where['like']['D.name'] = $this->input->get('customer_name');
        if ($this->input->get('charge_name')) $where['like']['E.fullname'] = $this->input->get('charge_name');
        if ($this->input->get('status')) $where['A.status'] = $this->input->get('status');
        
        $data['province'] = $this->input->get('province');
        $data['city'] = $this->input->get('city');
        $data['area'] = $this->input->get('area');
        $data['houses_name'] = $this->input->get('houses_name');
        $data['customer_name'] = $this->input->get('customer_name');
        $data['charge_name'] = $this->input->get('charge_name');
        $data['status'] = $this->input->get('status');
        
        $data['list'] = $this->Mhouses_assign->get_join_lists($where,[],$size,($page-1)*$size);
        $data_count = $this->Mhouses_assign->join_count($where);
        $data_count = $data_count[0]['count'];
        $data['data_count'] = $data_count;
        $data['page'] = $page;
       
        $where = [];
        $tmp_user = $this->Madmins->get_lists('id,fullname', $where);
        $data['user_list'] = array_column($tmp_user, 'fullname', 'id');
        
        //获取分页
        $pageconfig['base_url'] = "/housesconfirm";
        $pageconfig['total_rows'] = $data_count;
        $this->pagination->initialize($pageconfig);
        $data['pagestr'] = $this->pagination->create_links(); // 分页信息
        
        $this->load->view("housesconfirm/index", $data);
    }
    
    
    /*
     * 确认派单
     */
    public function do_confirm() {
    	
    	$where['is_del'] = 0;
    	if ($this->input->post('id')) {
    		$where['id'] = $this->input->post('id');
    		
    		$update_data['status'] = 3;
    		$res1 = $this->Mhouses_assign->update_info($update_data, $where);
    		
    		if($res1) {
    			if($this->input->post('order_id')) {
    				$where['status'] = 2;
    				$data_count = $this->Mhouses_assign->count($where);
    				if($data_count == 0) {
    					$where  = $update_data = [];
    					$where['id'] = $this->input->post('order_id');
    					$update_data['assign_status'] = 3;	//订单中的派单状态更新为已派单（已确认）
    					$update_data['order_status'] = 5;	//订单状态更新为派单完成
    					$res2 = $this->Mhouses_orders->update_info($update_data, $where);
    				}
    			}
    			
    			$this->return_json(['code' => 1, 'msg' => "确认派单成功！"]);
    			$this->write_log($data['userInfo']['id'],1,"确认派单楼盘：".$this->input->post('id'));
    		}
    		
    		$this->return_json(['code' => 0, 'msg' => "确认派单失败，请重试或联系管理员！"]);
    		
    	}
    	
    }
    
    
    /*
     * 验收图片
     * 1034487709@qq.com
     */
    public function check_upload_img(){
    	$data = $this->data;
    	$pageconfig = C('page.page_lists');
    	$this->load->library('pagination');
    	$page =  intval($this->input->get("per_page",true)) ?  : 1;
    	$size = $pageconfig['per_page'];
    	
    	$assign_id = $this->input->get('assign_id');
    	$order_id = $this->input->get('order_id');
    	$houses_id = $this->input->get('houses_id');
    	
    	if(IS_POST){
    		$post_data = $this->input->post();
    		foreach ($post_data as $key => $value) {
    			$where = array('order_id' => $order_id, 'assign_id' => $assign_id, 'point_id' => $key, 'type' => 1);
    			$img = $this->Mhouses_order_inspect_images->get_one('*', $where);
    
    			//如果是修改验收图片，则先删除该订单下所有验收图片，再重新添加
    			if ($img) {
    				$this->Mhouses_order_inspect_images->delete($where);
    			}
    
    			if (isset($value['front_img']) && count($value['front_img']) > 0) {
    				foreach ($value['front_img'] as $k => $v) {
    					$insert_data['order_id'] = $order_id;
    					$insert_data['assign_id'] = $assign_id;
    					$insert_data['point_id'] = $key;
    					$insert_data['front_img'] = $v;
    					$insert_data['back_img'] = isset($value['back_img'][$k]) ? $value['back_img'][$k] : '';
    					$insert_data['type'] = 1;
    					$insert_data['create_user'] = $insert_data['update_user'] = $data['userInfo']['id'];
    					$insert_data['create_time'] = $insert_data['update_time'] = date('Y-m-d H:i:s');
    					$this->Mhouses_order_inspect_images->create($insert_data);
    				}
    			}
    		}
    
    		
    
    		$this->write_log($data['userInfo']['id'], 2, "社区上传订单验收图片，订单id【".$order_id."】");
    		
    		$this->success("保存验收图片成功！");
    		exit;
    	}
    	
    	$order = $this->Mhouses_orders->get_one("*",array("id" => $order_id));
    	//获取该订单下面的所有楼盘
    	$points = $this->Mhouses_points->get_points_lists(['A.order_id' => $order_id, 'A.houses_id' => $houses_id],[],$size,($page-1)*$size);
    	$data_count = $this->Mhouses_points->count(['order_id' => $order_id, 'houses_id' => $houses_id]);
    	$data['page'] = $page;
    	$data['data_count'] = $data_count;
    	
    	//根据点位id获取对应的图片
    	$data['images'] = "";
    	if(count($points) > 0) {
    		$where['in'] = array("point_id"=>array_column($points,"id"));
    		$where['order_id'] = $order_id;
    		$where['assign_id'] = $assign_id;
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
    
    	$data['list'] = $list;
    	
    	//获取分页
    	$pageconfig['base_url'] = "/houses";
    	$pageconfig['total_rows'] = $data_count;
    	$this->pagination->initialize($pageconfig);
    	$data['pagestr'] = $this->pagination->create_links(); // 分页信息
    
    	$this->load->view('housesconfirm/check_adv_img', $data);
    }
    
    /**
     * 提交上画
     */
    public function submit_upload() {
    	$assign_id = $this->input->post('assign_id');
    	//如果全部上传完，则将派单表的状态改成已上画
    	$where_count['assign_id'] = $assign_id;
    	$where_count['front_img<>'] = '';
    	$tmp_count = $this->Mhouses_order_inspect_images->get_one('count(DISTINCT assign_id) as count', $where_count);
    	if(isset($tmp_count['count'])) {
    		$upload_count = $tmp_count['count'];
    	}
    	
    	$assign_count = $this->Mhouses_assign->get_one('points_count', ['id' => $assign_id, 'is_del' => 0]);
    	
    	if(isset($upload_count) && isset($assign_count['points_count']) && $upload_count != $assign_count['points_count']) {
    		$this->return_json(['code' => 0, 'msg' => "请确认你已经上传了所有点位的上画图片！"]);
    	}
    	
    	$update_data['status'] = 4;
    	$update_data['confirm_remark'] = '';
    	$res = $this->Mhouses_assign->update_info($update_data, ['id' => $assign_id]);
    	if($res) {
    		$this->return_json(['code' => 1, 'msg' => "已经提交上画至媒介管理人员处审核！"]);
    	}
    	
    }
    
}

