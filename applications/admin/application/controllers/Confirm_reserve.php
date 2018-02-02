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
        $this->load->view('confirm_reserve/detail', $data);
    }
    
    /**
     * 显示订单内指定楼盘的所有点位选择详情
     */
    public function houses_detail(){
        $data = $this->data;
        $houses_id = $data['houses_id'] = (int) $this->input->get('houses_id');
        $order_id = $data['order_id'] =  (int) $this->input->get('order_id');
        $data['houses_name'] = $this->input->get('houses_name');
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
        //获取点位列表
        $point_list = $this->Mhouses_points->get_lists('*', ['in' => ['id' => $point_ids]]);
        //找出该楼盘的点位
        $houses_ids = [];
        $confirm_point_num = 0;
        foreach ($point_list as $k => $v){
            if($v['houses_id'] != $houses_id){
                unset($point_list[$k]);
            }
            
        }
        $data['confirm_point_num'] = 0;
        foreach ($point_list as $k => $v){
            if(in_array($v['id'], $confirm_point_ids)){
                $data['confirm_point_num'] +=1;
            }
        }
        
        $data['all_point'] = implode(',', array_column($point_list, 'id'));
        $data['point_list'] = $point_list;
        $area_list = $this->Mhouses_area->get_lists('id,name', ['houses_id' => $houses_id]);
        if($area_list){
            foreach ($data['point_list'] as $k => $v){
                $data['point_list'][$k]['area_name'] = '';
                foreach ($area_list as $key => $val){
                    if($v['houses_id'] == $val['id']){
                        $data['point_list'][$k]['area_name'] = $val['name'];
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
            $this->load->view('confirm_reserve/sign', $data);
        }
    }
    
    /*
     * 预定订单转订单
     */
    public function checkout($id) {
    	$data = $this->data;
    	 
    	if(IS_POST) {
    		$post_data = $this->input->post();
    
    		$order_type = $post_data['order_type'];
    		$post_data['order_code'] = date('YmdHis').$post_data['customer_id']; //订单编号：年月日时分秒+客户id
    
    		if (isset($post_data['make_complete_time'])) {
    			$post_data['make_complete_time'] = $post_data['make_complete_time'].' '.$post_data['hour'].':'.$post_data['minute'].':'.$post_data['second'];
    		}
    
    		$post_data['creator'] =  $data['userInfo']['id'];
    		$post_data['create_time'] =  date('Y-m-d H:i:s');
    		unset($post_data['houses_id'], $post_data['area_id'],$post_data['ban'],$post_data['unit'],$post_data['floor'],$post_data['addr'], $post_data['hour'], $post_data['minute'], $post_data['second']);
    		unset($post_data['point_ids_old']);
    		$order_id = $this->Mhouses_orders->create($post_data);
    		if ($order_id) {
    			//如果选择的点位包含预定点位，则把对应的预定订单释放掉
    			$where['id'] = $id;
    			$info = $this->Mhouses_scheduled_orders->get_one("*", $where);
    			if ($info && count(array_intersect(explode(',', $post_data['point_ids']), explode(',', $info['point_ids']))) > 0) {
    				//释放该预定订单的所有点位
    				$update_data['decr'] = ['lock_num' => 1];//释放点位锁定数
    				$this->Mhouses_points->update_info($update_data, array('in' => array('id' => explode(',', $info['point_ids']))));
    
    				//更新该订单的状态为“已释放”
    				$this->Mhouses_scheduled_orders->update_info(array('order_status' => 5), array('id' => $info['id']));
    			}
    
    			//下单成功把选择的点增加占用客户，和增加上画次数
    			$update_data['joint']['`customer_id`'] = ','.$post_data['customer_id'];
    			//增加投放总量，一天为一次
    			$update_data['incr']['used_num'] = ceil( ($post_data['release_start_time']-$post_data['release_start_time']) / (24*3600) );
    			//增加点位可使用量1次，表示该点位少一次可放。
    			$update_data['incr']['`ad_use_num`'] = 1;
    			$this->Mhouses_points->update_info($update_data, array('in' => array('id' => explode(',', $post_data['point_ids']))));
    
    			//更新点位状态
    			$_where = [];
    			$_where['in'] = array('id' => explode(',', $post_data['point_ids']));
    			//字段的比较where['field']
    			$_where['field']['`ad_use_num`'] = '`ad_num`';
    			$this->Mhouses_points->update_info(['point_status' => 3], $_where);
    
    			$this->write_log($data['userInfo']['id'], 1, "社区资源管理转预定订单".$data['order_type_text'][$post_data['order_type']]."为订单,订单id【".$id."】");
    			$this->success("预定订单转订单成功！","/confirm_reserve");
    		} else {
    			$this->success("预定订单转订单失败！","/confirm_reserve");
    		}
    	}
    	 
    	if(!empty($id)) {
    		$where['id'] = $id;
    		$data['info'] = $this->Mhouses_scheduled_orders->get_one('*', $where);
    
    		//已选择点位列表
    		$where = [];
    		$where['in']['A.id'] = explode(',', $data['info']['confirm_point_ids']);
    		$data['selected_points'] = $this->Mhouses_points->get_points_lists($where);
    
    		if(!empty($data['info']['put_trade'])) {
    			$housesList = $this->Mhouses->get_lists("id, name,", ['put_trade<>' => $this->input->post('put_trade')]);
    		}else {
    			$housesList = $this->Mhouses->get_lists("id, name,", ['is_del' => 0]);
    		}
    
    		$data['order_type'] = $data['info']['order_type'];
    		$data['put_trade'] = $data['info']['put_trade'];
    		$data['housesList'] = $housesList;
    	}
    	 
    	$this->load->view('confirm_reserve/checkout', $data);
    }
}