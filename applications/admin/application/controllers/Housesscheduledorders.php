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
        $where = array();
        if ($this->input->get('order_type')) $where['A.order_type'] = $this->input->get('order_type');
        if ($this->input->get('lock_customer_id')) $where['A.lock_customer_id'] = $this->input->get('lock_customer_id');
        if ($this->input->get('admin_id')) $where['C.id'] = $this->input->get('admin_id');
        if ($this->input->get('order_status')) $where['A.order_status'] = $this->input->get('order_status');
        
        $data['order_type'] = $this->input->get('order_type');
        $data['lock_customer_id'] = $this->input->get('lock_customer_id');
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
        if(IS_POST){
            $post_data = $this->input->post();
            if (isset($post_data['area_id'])) unset($post_data['area_id']);
            //判断这个客户是否已锁定点位
            $order_type = (int) $post_data['order_type'];
            $where['is_del'] = 0;
            $where['lock_customer_id'] = $post_data['lock_customer_id'];
            $where['order_type'] = $order_type;
            $where['order_status'] = C('scheduledorder.order_status.code.in_lock');
            $count = $this->Mhouses_scheduled_orders->count($where);
            if ($count > 0) {
                $this->success("该客户已存在锁定中的".$data['order_type_text'][$order_type]."订单！", '/housesscheduledorders/addpreorder/'.$order_type);
                exit;
            }
            
            //判断该客户是否存在正在锁定日期范围内的已释放的订单
            $where['order_status'] = C('scheduledorder.order_status.code.done_release');
            $where['lock_end_time>'] = date('Y-m-d');
            $orderinfo = $this->Mhouses_scheduled_orders->get_one('*', $where);
            if ($orderinfo) {
                $this->success("该客户上一次释放的订单还未到锁定结束日期，不能新建预定订单！", '/housesscheduledorders/addpreorder/'.$order_type);
                exit;
            }
            
            $post_data['create_user'] = $post_data['update_user'] = $data['userInfo']['id'];
            $post_data['create_time'] = $post_data['update_time'] = date('Y-m-d H:i:s');
            $id = $this->Mhouses_scheduled_orders->create($post_data);
            if ($id) {
                //下单成功更新点位相关锁定字段
                $update_data['order_id'] = $id;
                $update_data['customer_id'] = $post_data['lock_customer_id'];
                $update_data['lock_start_time'] = $post_data['lock_start_time'];
                $update_data['lock_end_time'] = $post_data['lock_end_time'];
                $expire_time = strtotime($post_data['lock_end_time']." 23:59:59");
                $update_data['expire_time'] = $expire_time-86400;
                $update_data['is_lock'] = 1;
                $this->Mhouses_points->update_info($update_data, array('in' => array('id' => explode(',', $post_data['point_ids']))));
                
                $this->write_log($data['userInfo']['id'], 1, "新增".$data['order_type_text'][$post_data['order_type']]."预定订单,订单id【".$id."】");
                $this->success("添加成功！","/housesscheduledorders");
            } else {
                $this->success("添加失败！");
            }
        }
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