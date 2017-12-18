<?php 
/**
* 预定订单管理控制器
* @author yonghua 254274509@qq.com
*/
defined('BASEPATH') or exit('No direct script access allowed');
class Housesscheduledorders extends MY_Controller{

    public function __construct(){
        parent::__construct();
        $this->load->model([
            'Model_houses_scheduled_orders' => 'Mhouses_scheduled_orders',
            'Model_admins' => 'Madmins',
            'Model_medias' => 'Mmedias',
            'Model_houses' => 'Mhouses',
            'Model_houses_customers' => 'Mhouses_customers',
            'Model_houses_area' => 'Mhouses_area',
            'Model_salesman' => 'Msalesman',
            'Model_make_company' => 'Mmake_company',
            'Model_houses_points' => 'Mhouses_points'
        ]);
        $this->data['code'] = 'horders_manage';
        $this->data['active'] = 'housesscheduledorders_list';
        
        $this->data['customers'] = $this->Mhouses_customers->get_lists("id, name", array('is_del' => 0));  //客户
        $this->data['make_company'] = $this->Mmake_company->get_lists('id, company_name, business_scope', array('is_del' => 0));  //制作公司
        $this->data['order_type_text'] = C('order.houses_order_type'); //订单类型
        $this->data['salesman'] = $this->Msalesman->get_lists('id, name, sex, phone_number', array('is_del' => 0));  //业务员
    }
    
    /**
     * 预定订单首页
     */
    public function index(){
        $data = $this->data;
        $pageconfig = C('page.page_lists');
        $this->load->library('pagination');
        $page = $this->input->get_post('per_page') ? : '1';
        
        $order_type = $this->input->get('order_type');
        $customer_id = $this->input->get('customer_id');
        $order_status= $this->input->get('order_status');
        $admin_id = $this->input->get('admin_id');
        
        $where =  array();
        if ($order_type) $where['A.order_type'] = $order_type;
        if ($customer_id) $where['A.customer_id'] = $customer_id;
        if ($order_status) $where['A.order_status'] = $order_status;
        if ($admin_id) $where['C.id'] = $admin_id;
        
        $data['order_type'] = $this->input->get('order_type');
        $data['customer_id'] = $this->input->get('customer_id');
        $data['admin_id'] = $this->input->get('admin_id');
        $data['order_status'] = $this->input->get('order_status');
        
        $data['list'] = $this->Mhouses_scheduled_orders->get_order_lists($where, ($page-1)*$pageconfig['per_page'], $pageconfig['per_page']);
        $data_count = $this->Mhouses_scheduled_orders->get_order_count($where);
        $data['data_count'] = $data_count;
        $data['page'] = $page;
        
        //获取分页
        $pageconfig['base_url'] = "/housesscheduledorders";
        $pageconfig['total_rows'] = $data_count;
        $this->pagination->initialize($pageconfig);
        $data['pagestr'] = $this->pagination->create_links(); // 分页信息
        
        $data['status_text'] = C('order.order_status.text');
        
        $data['admins'] = $this->Madmins->get_lists('id, fullname', array('is_del' => 1));
        
        //获取所有客户
        $data['customer_list'] = $this->Mhouses_customers->get_lists('id, name', ['is_del' => 0]);
        
        $this->load->view('housesscheduledorders/index', $data);
    }
    
    /**
     * 选择预定订单类型
     */
    public function order_type() {
        $data = $this->data;
        $this->load->view('housesscheduledorders/order_type', $data);
    }
    
    /**
     * 添加预定订单
     * @param number $order_type
     */
    public function addpreorder($order_type=1){
        $data = $this->data;
        $data['order_type'] = $order_type;
        $data['status_text'] = C('order.order_status.text');
        //获取指定类型的点位
        $tmpPoints = $this->Mhouses_points->get_lists("id, houses_id, area_id", ['type_id' => $order_type, 'is_del' => 0]);
        if(count($tmpPoints) > 0) {
            $housesid = array_column($tmpPoints, 'houses_id');
            if(count($housesid)){
                $housesid = array_unique($housesid);
                $whereh['in']['id'] = $housesid;
                $data['housesList'] = $this->Mhouses->get_lists("id, name", $whereh);
            }
        }
        $this->load->view('housesscheduledorders/add', $data);
    }
}