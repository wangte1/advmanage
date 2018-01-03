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
        		
        ]);
        $this->data['code'] = 'horders_manage';
        $this->data['active'] = 'houses_confirm_list';

        $this->data['order_type_text'] = C('order.houses_order_type'); //订单类型
        $this->data['houses_assign_status'] = C('order.houses_assign_status'); //派单状态
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
    
}

