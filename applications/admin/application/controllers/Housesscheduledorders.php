<?php 
/**
* 预定订单管理控制器
* @author yonghua 254274509@qq.com
*/
use YYHSms\SendSms;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

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
            'Model_houses_points' => 'Mhouses_points',
            'Model_houses_points_format' => 'Mhouses_points_format'
        ]);
        $this->data['code'] = 'horders_manage';
        $this->data['active'] = 'housesscheduledorders_list';
        
        $this->data['customers'] = $this->Mhouses_customers->get_lists("id, name", array('is_del' => 0));  //客户
        $this->data['make_company'] = $this->Mmake_company->get_lists('id, company_name, business_scope', array('is_del' => 0));  //制作公司
        $this->data['order_type_text'] = C('order.houses_order_type'); //订单类型
        $this->data['salesman'] = $this->Madmins->get_lists('id, fullname as name, tel', array('is_del' => 1));  //业务员
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
    public function addpreorder($order_type=1, $put_trade=0){
        $data = $this->data;
        if(IS_POST){
            $post_data = $this->input->post();
            unset($post_data['ban'], $post_data['unit'], $post_data['floor']);
            if (isset($post_data['area_id'])) unset($post_data['area_id']);
            if (isset($post_data['addr'])) unset($post_data['addr']);
            
            $post_data['create_user'] = $post_data['update_user'] = $data['userInfo']['id'];
            $post_data['create_time'] = $post_data['update_time'] = date('Y-m-d H:i:s');
            $post_data['point_ids'] = implode(',', array_unique(explode(',', $post_data['point_ids'])));
            $post_data['confirm_point_ids'] = '';
            $id = $this->Mhouses_scheduled_orders->create($post_data);
            if ($id) {
                //更新点位的锁定数
                $update_data['incr']['lock_num'] = 1;
                $point_where['in'] = array('id' => explode(',', $post_data['point_ids']));
                $this->Mhouses_points->update_info($update_data, $point_where);
                
                //更新点位状态处理
                $_where['field']['`ad_num` ='] = '`lock_num`+`ad_use_num`';
                $_where['in'] = array('id' => explode(',', $post_data['point_ids']));
                $this->Mhouses_points->update_info(['point_status' => 3], $_where);

                $this->write_log($data['userInfo']['id'], 1, "新增".$data['order_type_text'][$post_data['order_type']]."预定订单,订单id【".$id."】");
                $this->success("添加成功！","/housesscheduledorders");
            } else {
                $this->success("添加失败！");
            }
        }
        //获取楼栋单元楼层列表
        $data['BUFL'] = $this->get_ban_unit_floor_list();
        $data['order_type'] = $order_type;
        $data['status_text'] = C('order.order_status.text');
       	
        
        $houses_list = $this->Mhouses->get_lists("id, name, put_trade", ['is_del' => 0]);
         
        //禁投放行业 begin
        if(count($houses_list) > 0) {
        	foreach ($houses_list as $k => $v) {
        		if(in_array($put_trade, explode(',', $v['put_trade']))) {
        			unset($houses_list[$k]);
        		}
        	}
        }
        
        $data['put_trade'] = $put_trade;
        $data['housesList'] = $houses_list;
        //获取excel导入的所有点位编号
        $codeList = $this->getPointsCodeFromExcel();
        if($codeList){
            $code_where['in']['A.code'] = array_unique($codeList);
            $code_where['A.type_id'] = $order_type;
            $data['point_list'] = $this->Mhouses_points->get_points_by_code($code_where);
        }
        $findList = array_column($data['point_list'], 'code');
        foreach ($codeList as $k => $v){
            if(!in_array($v, $findList)){
                echo $v.'--';
            }
        }
        //end
        //获取所有业务员
        $data['yewu'] = $this->Madmins->get_lists('id, fullname', array('group_id' => 2,'is_del' => 1));
        $this->load->view('housesscheduledorders/add', $data);
    }
    
    private function getPointsCodeFromExcel(){
        $codeList = [];
        $filename = './excel/code.xls';
        //加载phpexcel
        $this->load->library(['PHPExcel']);
        require_once './application/libraries/PHPExcel/IOFactory.php';
        $objReader = PHPExcel_IOFactory::createReader('Excel5');
        //加载目标Excel
        $objPHPExcel = $objReader->load($filename);
        //读取第一个sheet
        $sheet = $objPHPExcel->getSheet(0);
        // 取得总行数
        $highestRow = $sheet->getHighestRow();
        //从第一行开始读取数据
        for($j=1; $j <= $highestRow; $j++){
            $val = (int) $objPHPExcel->getActiveSheet()->getCell("A$j")->getValue();
            if(!empty($val) && $val){
                array_push($codeList, $val);
            }
        }
        return $codeList;
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
            if (isset($post_data['addr'])) unset($post_data['addr']);
            unset($post_data['id'], $post_data['ban'], $post_data['unit'], $post_data['floor']);
            
            //获取已被取消的点位
            $point_ids = $post_data['point_ids'];
            if(empty($point_ids)) $this->error("请至少选择一个点位！");
            $point_ids = explode(',', $point_ids);
            $point_ids_old= array_unique(explode(',', $post_data['point_ids_old']));
            
            $add = [];
            //判断没有新曾的点位
            foreach ($point_ids as $k => $v){
                if(!in_array($v, $point_ids_old)){
                    array_push($add, $v);
                }
            }
            if(!empty($add)){
                //点位锁定数+1
                $update_data['incr'] = ['lock_num' => 1];
                $this->Mhouses_points->update_info($update_data, array('in' => array('id' => $add)));
                //重置这些点位的状态
                $_where['field']['`ad_num`='] = '`lock_num`+`ad_use_num`';
                $_where['in'] = ['id' => $add];
                $this->Mhouses_points->update_info(['point_status' => 3], $_where);
                unset($update_data, $_where);
            }
            
            foreach ($point_ids as $k => $v){
                foreach ($point_ids_old as $key => $val){
                    if($v == $val){
                        unset($point_ids_old[$key]);
                    }
                }
            }
            
            if(!empty($point_ids_old)){
                //取消的点位锁定数-1
                $update_data['decr'] = ['lock_num' => 1];
                $this->Mhouses_points->update_info($update_data, ['in' => array('id' => $point_ids_old), 'lock_num >' => 0]);
                //重置这些点位的状态
                $_where['field']['`ad_num`>'] = '`lock_num`+`ad_use_num`';
                $_where['in'] = ['id' => $point_ids_old];
                $this->Mhouses_points->update_info(['point_status' => 1], $_where);
            }
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
            
            //获取楼栋单元楼层列表
            $data['BUFL'] = $this->get_ban_unit_floor_list();
            
            $data['customer'] = $this->Mhouses_customers->get_one('id, name', array('id' => $data['info']['lock_customer_id']));
            
            $data['order_type'] = $data['info']['order_type'];
            $data['put_trade'] = $data['info']['put_trade'];
            
            //禁投放行业 begin
            if($data['put_trade'] != 0) {
            	$data['housesList'] = $this->Mhouses->get_lists("id, name", ['put_trade<>' => $data['put_trade'],'is_del' => 0]);
            }else {
            	$data['housesList'] = $this->Mhouses->get_lists("id, name", ['is_del' => 0]);
            }
            //end
            //获取所有业务员
            $data['yewu'] = $this->Madmins->get_lists('id, fullname', array('group_id' => 2,'is_del' => 1));
            //已选择点位列表
            $where['in']['A.id'] = explode(',', $data['info']['point_ids']);
            $data['selected_points'] = $this->Mhouses_points->get_points_lists($where);

            $this->load->view("housesscheduledorders/add", $data);
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
        
        $update_data['lock_customer_id'] = '0';
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
        $info = $this->Mhouses_scheduled_orders->get_one("order_status, lock_end_time", ['id' => $id]);
        if($info['order_status'] !=2){
            $this->success('只有即将到期的订单才能够进行续期操作！', '/housesscheduledorders');
            exit;
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
    public function detail($id, $tab="") {
        $data = $this->data;
        $data['tab'] = 'basic';//默认显示基本信息tab
        if($tab && ($tab =='point' || $tab = 'confirm')) $data['tab'] = $tab;

        $pageconfig = C('page.page_lists');
        $this->load->library('pagination');
        $page = $this->input->get_post('per_page') ? : '1';
        
        if($this->input->get_post('per_page')){
            if($tab != 'point') $data['tab'] = 'point';
        }
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
                $pageconfig['base_url'] = "/housesscheduledorders/detail/{$id}/point";
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
        $houses_list = [];
        if(count($houses_ids)){
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
        }
        
        $data['houses_list'] = $houses_list;
        $this->load->view('housesscheduledorders/detail', $data);
    }
    
    
    /**
     * 显示订单内指定楼盘的所有点位选择详情
     */
    public function houses_detail(){
        $data = $this->data;
        $houses_id = $data['houses_id'] = (int) $this->input->get('houses_id');
        $order_id = $data['order_id'] =  (int) $this->input->get('order_id');
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

        $this->load->view('housesscheduledorders/houses_detail', $data);
    }
    
    /**
     * 全选选一个楼栋或反选
     */
    public function select_ban(){
        $status = (int) $this->input->post('status');
        $order_id = (int) $this->input->post('order_id');
        $area_id = (int) $this->input->post('area_id');
        $ban = (int) $this->input->post('ban');
        
        //根据订单id获取用户已确认的点位
        $orderInfo = $this->Mhouses_scheduled_orders->get_one('point_ids,confirm_point_ids,is_confirm', ['id' => $order_id]);
        if($orderInfo['is_confirm'] == 1){
            $this->return_json(['code' => 0, 'msg' => '您不能修改已确认的订单！']);
        }
        $point_ids = explode(',', $orderInfo['point_ids']);
        $confirm_point_ids = $orderInfo['confirm_point_ids'];
        
        if($confirm_point_ids){
            $confirm_point_ids = explode(',', $confirm_point_ids);
        }else{
            $confirm_point_ids = [];
        }
        
        //获取当前订单楼盘锁定点位的列表信息
        $point_list = $this->Mhouses_points->get_lists('id, ban, area_id', ['in' => ['id' => $point_ids]]);
        //找出该楼盘的点位
        $houses_ids = [];
        foreach ($point_list as $k => $v){
            if(($v['area_id'] == $area_id) && ($v['ban'] == $ban)){
                array_push($houses_ids, $v['id']);
            }
        }
        
        if($status){
            //如果是全选 合并，去重
            if(!empty($confirm_point_ids)){
                $confirm_point_ids = array_unique(array_merge($confirm_point_ids, $houses_ids));
            }else{
                $confirm_point_ids = $houses_ids;
            }
            $confirm_point_ids = implode(',', $confirm_point_ids);
        }else{
            //反选
            if($confirm_point_ids){
                foreach ($confirm_point_ids as $k => $v){
                    if(in_array($v, $houses_ids)){
                        unset($confirm_point_ids[$k]);
                    }
                }
            }
            if(count($confirm_point_ids) == 0){
                $confirm_point_ids = '';
            }else{
                $confirm_point_ids = implode(',', $confirm_point_ids);
            }
        }
        
        $res = $this->Mhouses_scheduled_orders->update_info(['confirm_point_ids' => $confirm_point_ids], ['id' => $order_id]);
        if(!$res){
            $this->return_json(['code' => 0, 'msg' => '操作失败']);
        }
        $this->return_json(['code' => 1, 'msg' => '操作成功']);
    }
    
    /**
     * 选取所有楼盘/反选
     */
    public function select_all_houses(){
        $status = (int) $this->input->post('status');
        $order_id = (int) $this->input->post('order_id');
      
        //根据订单id获取用户已确认的点位
        $orderInfo = $this->Mhouses_scheduled_orders->get_one('point_ids,confirm_point_ids,is_confirm', ['id' => $order_id]);
        if($orderInfo['is_confirm'] == 1){
            $this->return_json(['code' => 0, 'msg' => '您不能修改已确认的订单！']);
        }
        $confirm_point_ids = '';
        if($status){
            $confirm_point_ids = $orderInfo['point_ids'];
        }
        $res = $this->Mhouses_scheduled_orders->update_info(['confirm_point_ids' => $confirm_point_ids], ['id' => $order_id]);
        
        if(!$res){
            $this->return_json(['code' => 0, 'msg' => '操作失败']);
        }
        $this->return_json(['code' => 1, 'msg' => '操作成功']);
    }
    
    /**
     * 全选或全不选
     */
    public function select_all(){
        
        $status = (int) $this->input->post('status');
        $order_id = (int) $this->input->post('order_id');
        $houses_id = (int) $this->input->post('houses_id');
        
        //根据订单id获取用户已确认的点位
        $orderInfo = $this->Mhouses_scheduled_orders->get_one('point_ids,confirm_point_ids,is_confirm', ['id' => $order_id]);
        if($orderInfo['is_confirm'] == 1){
            $this->return_json(['code' => 0, 'msg' => '您不能修改已确认的订单！']);
        }
        $point_ids = explode(',', $orderInfo['point_ids']);
        $confirm_point_ids = $orderInfo['confirm_point_ids'];
        
        if($confirm_point_ids){
            $confirm_point_ids = explode(',', $confirm_point_ids);
        }
        
        //获取当前订单楼盘锁定点位的列表信息
        $point_list = $this->Mhouses_points->get_lists('id, houses_id', ['in' => ['id' => $point_ids]]);
        //找出该楼盘的点位
        $houses_ids = [];
        foreach ($point_list as $k => $v){
            if($v['houses_id'] == $houses_id){
                array_push($houses_ids, $v['id']);
            }
        }
        
        if($status){
            //如果是全选 合并，去重
            if(!empty($confirm_point_ids)){
                $confirm_point_ids = array_unique(array_merge($confirm_point_ids, $houses_ids));
            }else{
                $confirm_point_ids = $houses_ids;
            }
            $confirm_point_ids = implode(',', $confirm_point_ids);
        }else{
            //反选
            if($confirm_point_ids){
                foreach ($confirm_point_ids as $k => $v){
                    if(in_array($v, $houses_ids)){
                        unset($confirm_point_ids[$k]);
                    }
                }
            }
            if(count($confirm_point_ids) == 0){
                $confirm_point_ids = '';
            }else{
                $confirm_point_ids = implode(',', $confirm_point_ids);
            }
        }

        $res = $this->Mhouses_scheduled_orders->update_info(['confirm_point_ids' => $confirm_point_ids], ['id' => $order_id]);
        
        if(!$res){
            $this->return_json(['code' => 0, 'msg' => '操作失败']);
        }
        $this->return_json(['code' => 1, 'msg' => '操作成功']);
    }
    
    /**
     * 选择一页的所有点位
     */
    public function select_page_all(){
        
        $status = (int) $this->input->post('status');
        $order_id = (int) $this->input->post('order_id');
        $houses_id = (int) $this->input->post('houses_id');
        
        //根据订单id获取用户已确认的点位
        $orderInfo = $this->Mhouses_scheduled_orders->get_one('point_ids,confirm_point_ids,is_confirm', ['id' => $order_id]);
        if($orderInfo['is_confirm'] == 1){
            $this->return_json(['code' => 0, 'msg' => '您不能修改已确认的订单！']);
        }
        $point_ids = explode(',', $orderInfo['point_ids']);
        $confirm_point_ids = $orderInfo['confirm_point_ids'];
        
        if($confirm_point_ids){
            $confirm_point_ids = explode(',', $confirm_point_ids);
        }else{
            $confirm_point_ids = [];
        }
        
        //获取当前订单楼盘锁定点位的列表信息
        $point_list = $this->Mhouses_points->get_lists('id, houses_id', ['in' => ['id' => $point_ids]]);
        //找出该楼盘的点位
        $houses_ids = [];
        foreach ($point_list as $k => $v){
            if($v['houses_id'] == $houses_id){
                array_push($houses_ids, $v['id']);
            }
        }
        
        if($status){
            //如果是全选 合并，去重
            if(!empty($confirm_point_ids)){
                $confirm_point_ids = array_unique(array_merge($confirm_point_ids, $houses_ids));
            }else{
                $confirm_point_ids = $houses_ids;
            }
            $confirm_point_ids = implode(',', $confirm_point_ids);
        }else{
            //反选
            if($confirm_point_ids){
                foreach ($confirm_point_ids as $k => $v){
                    if(in_array($v, $houses_ids)){
                        unset($confirm_point_ids[$k]);
                    }
                }
            }
            if(count($confirm_point_ids) == 0){
                $confirm_point_ids = '';
            }else{
                $confirm_point_ids = implode(',', $confirm_point_ids);
            }
        }
        
        $res = $this->Mhouses_scheduled_orders->update_info(['confirm_point_ids' => $confirm_point_ids], ['id' => $order_id]);
        if(!$res){
            $this->return_json(['code' => 0, 'msg' => '操作失败']);
        }
        $this->return_json(['code' => 1, 'msg' => '操作成功']);
    }
    
    
    
    /**
     * 根据条件获取点位的列表和数量
     */
    public function get_points() {

        if($this->input->post('order_type')) $where['type_id'] = $this->input->post('order_type');
        if(!empty($this->input->post('houses_id'))) {$houses_id = $where['houses_id'] = $this->input->post('houses_id');}
        if(!empty($this->input->post('area_id'))) {$where['area_id'] = $this->input->post('area_id');}
        if(!empty($this->input->post('ban'))) $where['ban'] = $this->input->post('ban');
        if(!empty($this->input->post('unit'))) $where['unit'] = $this->input->post('unit');
        if(!empty($this->input->post('floor'))) $where['floor'] = $this->input->post('floor');
        if(!empty($this->input->post('addr'))) $where['addr'] = $this->input->post('addr');
        $lock_start_time = $this->input->post('lock_start_time');
        
        $where['is_del'] = 0;
        $where['point_status'] = 1;
        $fields = 'id,code,houses_id,area_id,ban,unit,floor,addr,type_id,ad_num, ad_use_num, point_status';
        $points_lists = $this->Mhouses_points->get_usable_point($fields, $where, $lock_start_time);
        if(count($points_lists) > 0) {
            $housesid = array_unique(array_column($points_lists, 'houses_id'));
            $area_id = array_unique(array_column($points_lists, 'area_id'));
            
            if(!empty($this->input->post('put_trade'))) {
            	$housesList = $this->Mhouses->get_lists("id, name,", ['in' => ['id' => $housesid], 'put_trade<>' => $this->input->post('put_trade')]);
            }else {
            	$housesList = $this->Mhouses->get_lists("id, name,", ['in' => ['id' => $housesid]]);
            }
            
            $wherea['in']['id'] = $area_id;
            $areaList = $this->Mhouses_area->get_lists("id, name", $wherea);
            //获取规格列表
            $size_list = $this->Mhouses_points_format->get_lists('id,type,size', ['is_del' => 0]);
            foreach ($points_lists as $k => &$v) {
                //设置状态
                $v['point_status_txt'] = C('public.points_status')[$v['point_status']];
                
                $mark = false;
                foreach($housesList as $k1 => $v1) {
                    if($v['houses_id'] == $v1['id']) {
                        $v['houses_name'] = $v1['name'];
                        $mark = true;
                        break;
                    }
                }
                
                if($mark == false) {
                	unset($points_lists[$k]);
                	continue;
                }
                
                foreach($areaList as $k2 => $v2) {
                    if($v['area_id'] == $v2['id']) {
                        $v['area_name'] = $v2['name'];
                        break;
                    }
                }
                
                $v['size'] = '';
                if($size_list){
                    foreach ($size_list as $key => $val){
                        if($val['type'] == $v['type_id']){
                            $v['size'] = $val['size'];break;
                        }
                    }
                }
            }
            
        }
        $areaList = [];
        if($houses_id){
            $areaList = $this->Mhouses_area->get_lists('id, name', ['houses_id' => $houses_id]);
        }
        //获取去重的组团区域
        $this->return_json(array('flag' => true, 'points_lists' => $points_lists, 'count' => count($points_lists), 'area_list' => $areaList));
    }
    
    
    /**
     * 导出预定点位列表
     */
    public function export($id, $type) {
        //加载phpexcel
        $this->load->library("PHPExcel");
        //设置表头
        $table_header =  array(
            '点位id' => 'id',
            '点位编号' => "code",
            '楼盘名称' => "houses_name",
            '组团' => 'houses_area_name',
            '楼栋' => 'ban',
            '单元' => 'unit',
            '楼层' => 'floor',
            '位置' => "addr",
            '价格' => 'price',
            '规格' => "size"
        );
        $i = 0;
        foreach($table_header as  $k=>$v){
            $cell = PHPExcel_Cell::stringFromColumnIndex($i).'1';
            $this->phpexcel->setActiveSheetIndex(0)->setCellValue($cell, $k);
            $i++;
        }
        
        $scheduledorder = $this->Mhouses_scheduled_orders->get_one('*', array('id' => $id));
        
        $where['in']['A.id'] = explode(',', $scheduledorder['confirm_point_ids']);
        
        $customers = $this->Mhouses_customers->get_one("name", array('id' => $scheduledorder['lock_customer_id'], 'is_del' => 0)); //客户
        
        $list = $this->Mhouses_points->get_points_lists($where);
        foreach ($list as $k => $v){
            if($v['addr'] == 1){
                $list[$k]['addr'] = '门禁';
            }else{
                $list[$k]['addr'] = '电梯前室';
            }
        }  
        $h = 2;
        foreach($list as $key=>$val){
            $j = 0;
            foreach($table_header as $k => $v){
                $cell = PHPExcel_Cell::stringFromColumnIndex($j++).$h;
                $value = $val[$v];
                $this->phpexcel->getActiveSheet(0)->setCellValue($cell, $value);
            }
            $h++;
        }
        
        $this->phpexcel->setActiveSheetIndex(0);
        // 输出
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename=预定点位表（客户：'.$customers['name'].'）.xls');
        header('Cache-Control: max-age=0');
        
        $objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel5');
        $tmpFileName = "./excel/".md5($id).".xls";
        $objWriter->save("$tmpFileName");
        $objWriter->save('php://output');

        $sales_id = $scheduledorder['sales_id'];
        $salesInfo = $this->Madmins->get_one('email', ['id' => $sales_id]);
        $user_id = $this->data['userInfo']['id'];
        
        if(!$salesInfo){
            $this->send(['uid'=> $user_id, 'message' => '业务员不存在,无法发送至邮箱']);
            exit;
        }
        if(!empty($salesInfo['email'])){
            if(!preg_match(C('regular_expression.email'), $salesInfo['email'])){
                //邮件发送失败
                $this->send(['uid'=> $user_id, 'message' => '邮件发送失败，请填写您的正确邮箱']);
                exit;
            }
            //发送到邮箱
            $subject = $customers['name']."的预定点位表";
            $body = "点击附件下载即可";
            $alt = "点击附件下载即可";
            $email = $salesInfo['email'];
            $file = $tmpFileName;
            $this->sendEmail($subject, $body, $alt, $email, $file);
            $this->send(['uid'=> $user_id, 'message' => '邮件已发送至 '.$salesInfo['email']]);
        }else{
            //邮件发送失败
            $this->send(['uid'=> $user_id, 'message' => '邮件发送失败，请先完善您的邮件信息！']);
        }
        //删除文件
        unlink($file);
    }
    
    /**
     * 获取楼栋，单元， 楼层列表
     * @author yonghua 254274509@qq.com
     * @return array[]|array[]
     */
    private function get_ban_unit_floor_list(){
        $array = [];
        
        $list = $this->Mhouses_points->get_lists(
            'ban, unit, floor', 
            [
                'ban !=' => '',
                'unit !=' => '',
                'floor !=' => '',
                'is_del' => 0
            ], 
            [
                'ban' => 'asc',
                'unit' => 'asc',
                'floor' => 'asc',
            ]
        );
        if(!$list) return $array;
        $array['ban'] = array_unique(array_column($list, 'ban'));
        $array['unit'] = array_unique(array_column($list, 'unit'));
        $array['floor'] = array_unique(array_column($list, 'floor'));
        
        return $array;
    }
    
    
    
    /**
     * 给业务员发送短信
     * @author yonghua 254274509@qq.com
     */
    public function sendMsg(){

        $sales_id = intval($this->input->post('sales_id'));
        $info = $this->Madmins->get_one('tel, fullname', ['id' => $sales_id]);
        if(!$info) $this->return_json(['code' => 0, 'msg' => '业务员不存在']);
        if(empty($info['tel'])){
            $this->return_json(['code' => 0, 'msg' => '电话不能为空！']);
        }
        if(!preg_match('/^1[3|4|5|8|7][0-9]\d{8}$/', $info['tel'])){
            $this->return_json(['code' => 0, 'msg' => '业务员手机号格式不正确！']);
        }
        // 配置短信信息
        $app = C('sms.app');
        $parems = [
            'PhoneNumbers' => $info['tel'],
            'SignName' => C('sms.sign.lkcb'),
            'TemplateCode' => C('sms.template.yewuyuan'),
            'TemplateParam' => array(
                'name' => $info['fullname']
            )
        ];
        //发送短信
        set_time_limit(0);
        $sms = new SendSms($app, $parems);
        try {
            $info = (array) $sms->send();
            if(isset($info['Code'])) {
                if(strtolower($info['Code']) == 'ok'){
                    $this->return_json(['code' => 1, 'msg' => '发送成功']);
                }else{
                    $this->return_json(['code' => 0, 'msg' => '错误码：'.$info['Code']]);
                }
            }
            $this->return_json(['code' => 0, 'msg' => '请稍后重试']);
        } catch (Exception $e) {
            $this->return_json(['code' => 0, 'msg' => $e->getMessage()]);
        }
        
    }
    
    /**
     * 将url转换成短网址，避免被屏蔽
     * @author yonghua 254274509@qq.com
     * @param string $url
     * @throws Exception
     * @return number[]|string[]|number[]|mixed[]
     */
    private function getShortUrl($url=""){
        if(empty($url)) return ['code' => 0, 'msg' => 'url不能为空'];
        $key = C('short_url.user_key');
        $apiurl = 'https://ni2.org/api/create.json';
        $info = Http::Request($apiurl, ['url' => $url ,'user_key' => $key], 'POST');
        if(!$info) throw new Exception('无法连接api服务器');
        $info = (array) json_decode($info);
        if($info['result'] == 0){
            return ['code' => 1, 'url' => $info['url']];
        }
        $error = '';
        switch ($info['result']){
            case 1 :
                $error = '生成URL失败';
                break;
            case 2 :
                $error = 'URL不符合格式';
                break;
            case 3 :
                $error = '该域名已被加入黑名单';
                break;
        }
        return ['code' => 0, 'msg' => $error];
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
                    unset($update_data['decr']);
                    //更新该订单的状态为“已释放”
                    $this->Mhouses_scheduled_orders->update_info(array('order_status' => 5), array('id' => $info['id']));
                }
                
                //下单成功把选择的点增加占用客户，和增加上画次数
                $update_data['joint']['`customer_id`'] = ','.$post_data['customer_id'];
                //增加投放总量，一天为一次
                $update_data['incr']['used_num'] = ceil( ( strtotime($post_data['release_end_time']) - strtotime($post_data['release_start_time'])) / (24*3600) );
                //增加点位可使用量1次，表示该点位少一次可放。
                $update_data['incr']['`ad_use_num`'] = 1;
                $this->Mhouses_points->update_info($update_data, array('in' => array('id' => explode(',', $post_data['point_ids']))));

                //更新点位状态
                $_where = [];
                $_where['in'] = array('id' => explode(',', $post_data['point_ids']));
                //字段的比较where['field']
                $_where['field']['`ad_num`'] = '`ad_use_num` + `lock_num`';
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
        
        $this->load->view('housesscheduledorders/checkout', $data);
    }
    
    
    private function sendEmail($subect="", $body="", $alt="", $email="", $file=""){
        
        $mail = new PHPMailer(true);                              // Passing `true` enables exceptions
        $config = C('smtp');
        try {
            //Server settings
            $mail->SMTPDebug = 1;                                 // Enable verbose debug output
            $mail->isSMTP();                                      // Set mailer to use SMTP
            $mail->Host = $config['host'];                    // Specify main and backup SMTP servers
            $mail->SMTPAuth = true;                               // Enable SMTP authentication
            $mail->Username = $config['user'];                 // SMTP username
            $mail->Password = $config['passwd'];                           // SMTP password
            $mail->Port = 80;                                     // TCP port to connect to
            
            //Recipients
            $mail->setFrom($config['user'], $config['nickname']);
            $mail->addAddress($email);
            
            //Attachments
            if(@is_file($file)){
                $mail->addAttachment($file);            // Add attachments
            }
            
            //Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = $subect;
            $mail->Body    = $body;
            $mail->AltBody = $alt;
            
            $mail->send();
        } catch (Exception $e) {
            echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
        }
    }
}