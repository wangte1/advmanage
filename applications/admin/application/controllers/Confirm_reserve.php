<?php 
/**
 * 预定订单确定管理控制器
 * @author yonghua 254274509@qq.com
 */
use YYHSms\SendSms;

defined('BASEPATH') or exit('No direct script access allowed');
class Confirm_reserve extends MY_Controller{
    
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
            'Model_houses_points' => 'Mhouses_points',
            'Model_houses_points_format' => 'Mhouses_points_format'
        ]);
        $this->data['code'] = 'horders_manage';
        $this->data['active'] = 'confirm_reserve_list';
        
        $this->data['customers'] = $this->Mhouses_customers->get_lists("id, name", array('is_del' => 0));  //客户
        $this->data['make_company'] = $this->Mmake_company->get_lists('id, company_name, business_scope', array('is_del' => 0));  //制作公司
        $this->data['order_type_text'] = C('order.houses_order_type'); //订单类型
        $this->data['salesman'] = $this->Msalesman->get_lists('id, name, sex, phone_number', array('is_del' => 0));  //业务员
        $this->data['point_addr'] = C('housespoint.point_addr');	//点位位置
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
        if ($this->input->get('customer_id')) $where['A.lock_customer_id'] = $this->input->get('customer_id');
        if ($this->input->get('admin_id')) $where['C.id'] = $this->input->get('admin_id');
        if ($this->input->get('sales_id')) $where['A.sales_id'] = $data['sales_id'] =  $this->input->get('sales_id');
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
        $data['confirm_text'] = C('housesscheduledorder.customer_status');
        
        $data['admins'] = $this->Madmins->get_lists('id, group_id, fullname', array('is_del' => 1));
        
        //获取所有客户
        $data['customer_list'] = $this->Mhouses_customers->get_lists('id, name', ['is_del' => 0]);
        
        $this->load->view('confirm_reserve/index', $data);
    }
    
    /**
     * 确定预定订单
     */
    public function detail($id=0, $tab=''){
        
        $data = $this->data;
        
        $data['tab'] = 'basic';//默认显示基本信息tab
        if($tab && ($tab =='point' || $tab = 'confirm')) $data['tab'] = $tab;
        
        $pageconfig = C('page.page_lists');
        $this->load->library('pagination');
        $page = $this->input->get_post('per_page') ? : '1';
        $size = $pageconfig['per_page'] = 15;
        $where = array();
        
        $data['info'] = $this->Mhouses_scheduled_orders->get_one('*', array('id' => $id));
        $ret = strtotime($data['info']['lock_end_time']) - strtotime(date('Y-m-d'));
        //预定客户
        $data['info']['customer_name'] = $this->Mhouses_customers->get_one('name', array('id' => $data['info']['lock_customer_id']))['name'];
        
        $data['status_text'] = C('housesscheduledorder.order_status.text'); //订单状态
        //将点位分割成数组，再按15个作为分组作为第一页的点位id
        $point_ids = array_chunk(explode(',', $data['info']['point_ids']), $size);
        //预定点位列表
        $data['info']['selected_points'] = [];
        if(isset($point_ids[($page-1)])){
            $point_list = $this->Mhouses_points->get_points_lists_page(
                [
                    'in' => array(
                        'A.id' => $point_ids[($page-1)] //模拟分页
                    )
                ],
                [
                    'A.id' => 'asc'
                ]
                );
            
            if($point_list){
                $data['info']['selected_points'] = $point_list;
                //模拟获取分页
                $data['page'] = $page;
                $totalCount = count(explode(',', $data['info']['point_ids']));
                $data['data_count'] = $totalCount;
                $pageconfig['base_url'] = "/housesscheduledorders/detail/{$id}/tab";
                $pageconfig['total_rows'] = $totalCount;
                $this->pagination->initialize($pageconfig);
                $data['pagestr'] = $this->pagination->create_links();// 分页信息
            }
        }
        
        #客户确认
        //获取所有预约锁定点位
        $point_ids = $data['info']['point_ids'];
        $point_ids = explode(',', $point_ids);
        
        $confirm_point_ids = $data['info']['confirm_point_ids'];
        
        
        $point_all = $this->Mhouses_points->get_lists('id, houses_id', ['in' => ['id' => $point_ids]]);
        if(!empty($confirm_point_ids)){
            $confirm_point_all = [];
            $confirm_point_ids = array_unique(explode(',', $confirm_point_ids));
            foreach ($point_all as $k => $v){
                if(in_array($v['id'], $confirm_point_ids)){
                    array_push($confirm_point_all, $v);
                }
            }
        }
        //获取以上点位包含的楼盘id
        $houses_ids = array_unique(array_column($point_all, 'houses_id'));
        //获取这些楼盘信息
        $houses_list = $this->Mhouses->get_lists('id, name, province, city, area', ['in' => ['id' => $houses_ids]]);
        foreach ($houses_list as $k => $v){
            $houses_list[$k]['num'] = 0;
            $houses_list[$k]['confirm_num'] = 0;
            foreach ($point_all as $key => $val){
                if($v['id'] == $val['houses_id']){
                    $houses_list[$k]['num'] +=1;
                }
            }
            if(isset($confirm_point_all) && $confirm_point_all){
                foreach ($confirm_point_all as $key => $val){
                    if($v['id'] == $val['houses_id']){
                        $houses_list[$k]['confirm_num'] +=1;
                    }
                }
            }
        }
        
        $data['houses_list'] = $houses_list;
        
        $orderInfo = $data['info'];
        $data['is_all'] = 0;
        $confirm_num = 0;
        if($orderInfo['confirm_point_ids']){
            $confirm_num = count(explode(',', $orderInfo['confirm_point_ids']));
        }
        $no_confirm_num = 0;
        if($orderInfo['point_ids']){
            $no_confirm_num = count(explode(',', $orderInfo['point_ids']));
        }
        if($confirm_num == $no_confirm_num) $data['is_all'] = 1;
        $this->load->view('confirm_reserve/detail', $data);
    }
    
    /**
     * 显示订单内指定楼盘的所有点位选择详情
     */
    public function houses_detail(){
        $data = $this->data;
        $point_where = [];//点位查询条件
        if($this->input->get('area_id')) $point_where['area_id'] = $data['area_id'] = $area_id = $this->input->get('area_id');
        if($this->input->get('ban')) $point_where['ban'] = $data['ban'] = $ban = $this->input->get('ban');
        
        $houses_id = $point_where['houses_id'] = $data['houses_id'] = (int) $this->input->get('houses_id');
        $order_id = $data['order_id'] =  (int) $this->input->get('order_id');
        $data['houses_name'] = $houses_name = $this->input->get('houses_name');
        //获取该订单的所有锁定点位，和已确认点位
        $orderInfo = $this->Mhouses_scheduled_orders->get_one('point_ids,confirm_point_ids', ['id' => $order_id]);
        
        //已锁定的点位
        $point_ids = explode(',', $orderInfo['point_ids']);
        //已确认的点位
        $confirm_point_ids = $orderInfo['confirm_point_ids'];
        if($confirm_point_ids){
            $confirm_point_ids = explode(',', $confirm_point_ids);
            $data['confirm_point_ids'] = $confirm_point_ids;
        }else{
            $confirm_point_ids = [];
            $data['confirm_point_ids'] = [];
        }
        $point_where['in']= ['id' => $point_ids];
        $point_list = $this->Mhouses_points->get_lists('houses_id,area_id,ban,count(`ban`) as num', $point_where, ['ban' => 'asc'], 0, 0, 'ban');
        $data['list'] = $point_list;
        //获取所有组团名称
        $area_ids = array_unique(array_column($point_list, 'area_id'));
        $area_list = $this->Mhouses_area->get_lists('id,name', ['in' => ['houses_id' => $area_ids] ]);
        if($area_list){
            foreach ($point_list as $k => $v){
                $data['list'][$k]['area_name'] = '';
                $data['list'][$k]['select_num'] = 0;
                foreach ($area_list as $key => $val){
                    if($v['houses_id'] == $val['id']){
                        $data['list'][$k]['area_name'] = $val['name'];
                    }
                }
            }
        }
        
        //查询点位，统计
        if(count($confirm_point_ids)){
            $point_where['in'] = ['id' => $confirm_point_ids];
            $point_lists = $this->Mhouses_points->get_lists('ban', $point_where);
            foreach ($data['list'] as $k => $v){
                foreach ($point_lists as $key => $val){
                    if($v['ban'] == $val['ban']){
                        $data['list'][$k]['select_num'] +=1;
                    }
                }
            }
        }
        $this->load->view('confirm_reserve/houses_detail', $data);
    }
    
    /**
     * 客户确认
     */
    public function sign(){
        if(IS_POST){
            $order_id = $this->input->post('id');
            $img = trim($this->input->post('confirm_img'));
            if(empty($img)){
                $this->error('合同照片不能为空，请上传！');
            }
            $post = $this->input->post();
            $post['is_confirm'] = 1;
            unset($post['id'], $post['contact_person'], $post['file']);
            $res = $this->Mhouses_scheduled_orders->update_info($post, ['id' => $order_id]);
            if(!$res){
                $this->error('操作失败');
            }
            $this->success('操作成功', '/confirm_reserve/detail/'.$order_id);
        }else{
            $data = $this->data;
            $order_id = $this->input->get('order_id');
            $data['order_id'] = $order_id;
            $orderInfo = $this->Mhouses_scheduled_orders->get_one('*', ['id' =>$order_id]);
            if(!$orderInfo) show_404();
            $customer = $this->Mhouses_customers->get_one('id,name,contact_person', ['id' => $orderInfo['lock_customer_id']]);
            $data['orderInfo'] = $orderInfo;
            $data['customer'] = $customer;
            if($orderInfo['is_confirm'] == 1){
                $this->load->view('confirm_reserve/has_sign', $data);
            }else{
                $this->load->view('confirm_reserve/sign', $data);
            }
        }
    }
}