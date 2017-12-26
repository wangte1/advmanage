<?php 
/**
* 订单管理控制器
* @author 867332352@qq.com
*/
defined('BASEPATH') or exit('No direct script access allowed');
class Housesassign extends MY_Controller{

    public function __construct(){
        parent::__construct();
        $this->load->model([
            'Model_houses_orders' => 'Mhouses_orders',
        	'Model_houses_points_format' => 'Mhouses_points_format',
            'Model_houses_customers' => 'Mhouses_customers',
        	'Model_houses' => 'Mhouses',
        	'Model_houses_area' => 'Mhouses_area',
        	'Model_houses_points' => 'Mhouses_points',
        	'Model_salesman' => 'Msalesman',
        	'Model_make_company' => 'Mmake_company',
        	'Model_admins' => 'Madmins',
        	'Model_houses_order_inspect_images' => 'Mhouses_order_inspect_images',
        	'Model_status_operate_time' => 'Mstatus_operate_time',
        	'Model_houses_change_points_record' => 'Mhouses_change_points_record',
        	'Model_houses_orders_log' => 'Mhouses_orders_log',
        	'Model_houses_order_inspect_images_log' => 'Mhouses_order_inspect_images_log',
        	'Model_houses_change_pic_orders' => 'Mhouses_change_pic_orders',
        	'Model_houses_scheduled_orders' => 'Mhouses_scheduled_orders'
        		
        ]);
        $this->data['code'] = 'horders_manage';
        $this->data['active'] = 'houses_assign_list';

        $this->data['customers'] = $this->Mhouses_customers->get_lists("id, name", array('is_del' => 0));  //客户
        $this->data['make_company'] = $this->Mmake_company->get_lists('id, company_name, business_scope', array('is_del' => 0));  //制作公司
        $this->data['order_type_text'] = C('order.houses_order_type'); //订单类型
        $this->data['houses_assign_status'] = C('order.houses_assign_status'); //派单状态
        $this->data['salesman'] = $this->Msalesman->get_lists('id, name, sex, phone_number', array('is_del' => 0));  //业务员
    }
    

    /**
     * 订单列表
     */
    public function index() {
        $data = $this->data;
        $pageconfig = C('page.page_lists');
        $this->load->library('pagination');
        $page = $this->input->get_post('per_page') ? : '1';

        $where =  array();
        
        //$where['A.is_del'] = 0;
        if ($this->input->get('order_code')) $where['A.order_code'] = $this->input->get('order_code');
        if ($this->input->get('order_type')) $where['A.order_type'] = $this->input->get('order_type');
        if ($this->input->get('customer_id')) $where['A.customer_id'] = $this->input->get('customer_id');
        if ($this->input->get('assign_status')) $where['A.assign_status'] = $this->input->get('assign_status');

        //即将到期
        $data['expire_time'] = $this->input->get("expire_time");
        if($this->input->get("expire_time")) {
            $where['A.release_end_time>='] = date("Y-m-d");
            $where['A.release_end_time<='] =  date("Y-m-d",strtotime("+7 day"));
            $where['A.order_status'] =  C('order.order_status.code.in_put');
        }

        //已到期未下画
        $data['overdue'] = $this->input->get('overdue');
        if($this->input->get("overdue")) {
            $where['A.release_end_time<'] =  date("Y-m-d");
            $where['A.order_status'] =  C('order.order_status.code.in_put');
        }

        $data['order_code'] = $this->input->get('order_code');
        $data['order_type'] = $this->input->get('order_type');
        $data['customer_id'] = $this->input->get('customer_id');
        $data['assign_status'] = $this->input->get('assign_status');

        //$data['project'] = array_column($this->Mcustomer_project->get_lists('id, project_name', array('is_del' => 0)), 'project_name', 'id');

        $data['list'] = $this->Mhouses_orders->get_order_lists($where, ($page-1)*$pageconfig['per_page'], $pageconfig['per_page']);
        $data_count = $this->Mhouses_orders->get_order_count($where);
        $data['data_count'] = $data_count;
        $data['page'] = $page;

        //获取分页
        $pageconfig['base_url'] = "/housesorders";
        $pageconfig['total_rows'] = $data_count;
        $this->pagination->initialize($pageconfig);
        $data['pagestr'] = $this->pagination->create_links(); // 分页信息

        $admins = $this->Madmins->get_lists("id,name");
        $data['admins'] = array_column($admins,"name","id");
        $data['status_text'] = C('order.order_status.text');
        
        $this->load->view("housesassign/index", $data);
    }
    
    /*
     * 派单
     */
    public function assign() {
    	$data = $this->data;
    	$this->load->view('housesassign/assign', $data);
    }

}

