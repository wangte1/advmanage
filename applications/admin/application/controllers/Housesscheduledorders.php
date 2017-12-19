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
        $data['lock_customer_id'] = $data['customer_id']= $this->input->get('customer_id');
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
        
        $data['status_text'] = C('housesscheduledorder.order_status.text');
        
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
    
    /*
     * 编辑订单
     */
    public function edit($id) {
        $data = $this->data;
        $data['info'] = $this->Mhouses_scheduled_orders->get_one("*", array('id' => $id));
        if ($data['info']['order_status'] == C('scheduledorder.order_status.code.done_release')) {
            $this->success('只有锁定中的订单才能够进行修改操作！', '/housesscheduledorders');
            exit;
        }
        
        if (IS_POST) {
            $post_data = $this->input->post();
            $post_data['update_user'] = $data['userInfo']['id'];
            $post_data['update_time'] = date('Y-m-d H:i:s');
            $id = $post_data['id'];
            unset($post_data['id']);
            //先把之前所有已选择的点位的状态置为未锁定，再把重新选择的点位状态置为锁定
            //此处要求最好锁表，以免刚释放的点位被他人占用
            //禁止其他人写入
            $this->db->query('lock table t_houses_points read');
            $this->db->query('lock table t_houses_points write');
            $this->Mhouses_points->update_info(
                array(
                    'order_id' => '0', 
                    'customer_id' => '0', 
                    'is_lock' => 0  
                ),
                array(
                    'in' => array(
                        'id' => explode(',', $post_data['point_ids_old'])
                    )
                )
            );
            $update_data['order_id'] = $id;
            $update_data['customer_id'] = $post_data['lock_customer_id'];
            $update_data['is_lock'] = 1;
            
            $this->Mhouses_points->update_info($update_data, array('in' => array('id' => explode(',', $post_data['point_ids']))));
            //释放
            $this->db->query('unlock table');
            unset($post_data['point_ids_old']);
            unset($post_data['area_id']);
            $result = $this->Mhouses_scheduled_orders->update_info($post_data, array('id' => $id));
            if ($result) {
                $this->write_log($data['userInfo']['id'], 2, "编辑".$data['order_type_text'][$post_data['order_type']]."订单,订单id【".$id."】");
                $this->success("修改成功！","/housesscheduledorders");
            } else {
                $this->error("修改失败！请重试！");
            }
        } else {
            $data['customer'] = $this->Mhouses_customers->get_one('id, name', array('id' => $data['info']['lock_customer_id']));
            
            $data['order_type'] = $data['info']['order_type'];
            
            //楼盘列表
            $data['houses_list'] = $this->Mhouses->get_lists("id, name", array('is_del' => 0));
            
            //已选择点位列表
            $where['in']['A.id'] = explode(',', $data['info']['point_ids']);
            $data['selected_points'] = $this->Mhouses_points->get_points_lists($where);

            $this->load->view("housesscheduledorders/edit", $data);
        }
    }
    
    
    /**
     * 释放订单
     */
    public function release_points($id) {
        $info = $this->Mhouses_scheduled_orders->get_one("*", array('id' => $id));
        
        if ($info['order_status'] == C('housesscheduledorder.order_status.code.done_release')) {
            $this->error('解除锁定失败！请重试！', '/housesscheduledorders');
            exit;
        }
        
        if ($this->data['userInfo']['id'] != 1 && $info['create_user'] != $this->data['userInfo']['id']) {
            $this->error('您只能解除自己下的预定订单！', '/housesscheduledorders');
            exit;
        }
        
        $update_data['order_id'] = $update_data['customer_id'] = '0';
        $update_data['is_lock'] = 0;
        $result = $this->Mhouses_points->update_info($update_data, array('in' => array('id' => explode(',', $info['point_ids']))));
        if (!$result) {
            $this->error('解除锁定失败！请重试！', '/housesscheduledorders');
        }
        
        //更新该订单的状态为“已释放”
        $this->Mhouses_scheduled_orders->update_info(array('order_status' => C('housesscheduledorder.order_status.code.done_release')), array('id' => $id));
        
        $this->success('解除锁定成功！已释放该订单的所有预定点位！', '/housesscheduledorders');
    }
    
    /**
     * 订单续期
     * @author yonghua
     */
    public function update_points($id) {
        $data = $this->data;
        $info = $this->Mhouses_scheduled_orders->get_one("order_status", ['id' => $id]);
        if($info['order_status'] != 2){
            $this->error('您只能续期即将到期的订单！', '/housesscheduledorders');
        }
        $time = date('Y-m-d');
        $end = date('Y-m-d', strtotime('+7 days'));
        $up =[
            'order_status' => 1,
            'lock_start_time' => $time,
            'lock_end_time' => $end,
            'update_time' => date('Y-m-d H:i:s'),
            'update_user' => $data['userInfo']['id']
        ];
        $res = $this->Mhouses_scheduled_orders->update_info($up, ['id' => $id]);
        if(!$res){
            $this->error('操作失败！', '/housesscheduledorders');
        }
        $this->success('续期成功！', '/housesscheduledorders');
    }
    
    
    /**
     * 预定订单详情
     */
    public function detail($id) {
        $data = $this->data;
        $data['info'] = $this->Mhouses_scheduled_orders->get_one('*', array('id' => $id));
        //预定客户
        $data['info']['customer_name'] = $this->Mhouses_customers->get_one('name', array('id' => $data['info']['lock_customer_id']))['name'];
        
        $data['status_text'] = C('housesscheduledorder.order_status.text'); //订单状态
        
        //预定点位列表
        $data['info']['selected_points'] = $this->Mhouses_points->get_points_lists(array('in' => array('A.id' => explode(',', $data['info']['point_ids']))));
        $this->load->view('housesscheduledorders/detail', $data);
    }
    
    /**
     * 根据条件获取点位的列表和数量
     */
    public function get_points() {
        $where['is_del'] = 0;
        if($this->input->post('order_type')) $where['type_id'] = $this->input->post('order_type');
        if($this->input->post('houses_id')) $where['houses_id'] = $this->input->post('houses_id');
        //预定订单只查询没有被锁定的点位
        $where['is_lock'] = 0;
        
        $points_lists = $this->Mhouses_points->get_lists("id,code,houses_id,area_id", $where);
        
        if(count($points_lists) > 0) {
            
            $housesid = array_column($points_lists, 'houses_id');
            $area_id = array_column($points_lists, 'area_id');
            
            $whereh['in']['id'] = $housesid;
            $housesList = $this->Mhouses->get_lists("id, name", $whereh);
            
            $wherea['in']['id'] = $area_id;
            $areaList = $this->Mhouses_area->get_lists("id, name", $wherea);
            
            foreach ($points_lists as $k => &$v) {
                foreach($housesList as $k1 => $v1) {
                    if($v['houses_id'] == $v1['id']) {
                        $v['houses_name'] = $v1['name'];
                        break;
                    }
                }
                
                foreach($areaList as $k2 => $v2) {
                    if($v['area_id'] == $v2['id']) {
                        $v['area_name'] = $v2['name'];
                        break;
                    }
                }
                
                
            }
        }
        $areaList = array_unique(array_column($points_lists, 'area_name'));
        //获取去重的楼盘区域
        $this->return_json(array('flag' => true, 'points_lists' => $points_lists, 'count' => count($points_lists), 'area_list' => $areaList));
    }
}