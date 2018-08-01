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
            'Model_houses_points_format' => 'Mhouses_points_format',
            'Model_houses_points_report' => 'Mhouses_points_report'
        ]);
        $this->data['code'] = 'horders_manage';
        $this->data['active'] = 'housesscheduledorders_list';
        
        $this->data['customers'] = $this->Mhouses_customers->get_lists("id, name", array('is_del' => 0, 'is_self' => 0));  //客户
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
        
        $schedule_start = $this->input->get('schedule_start');
        $schedule_end = $this->input->get('schedule_end');
        
        if($schedule_start){
            $data['schedule_start'] = $schedule_start;
            if($schedule_end){
                $where['`A.schedule_start`>='] = $schedule_start;
            }else{
                $where['`A.schedule_start`'] = $schedule_start;
            }
        }
        
        if($schedule_end){
            $data['schedule_end'] = $schedule_end;
            if($schedule_start){
                $where['`A.schedule_end`<='] = $schedule_end;
            }else{
                $where['`A.schedule_end`'] = $schedule_end;
            }
        }
        
        
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
            
            //定义已新增和被删除
            $add = $del = [];
            
            $point_ids = $post_data['point_ids'];
            if(empty($point_ids)) $this->error("请至少选择一个点位！");
            $point_ids = explode(',', $point_ids);
            //去重
            $point_ids = array_unique($point_ids);
            $point_ids_old = array_unique(explode(',', $post_data['point_ids_old']));
            
            //如果新点位不在旧点位数组里，则表示是新点位
            foreach ($point_ids as $k => $v){
                if(!in_array($v, $point_ids_old)){
                    array_push($add, $v);
                }
            }
            //找出旧数组的对新数组的差集，去除新增的点位，剩下的为删除的点位
            $diff = array_diff($point_ids_old, $point_ids);
            if(count($diff)){
                foreach ($diff as $k => $v){
                    if(!in_array($v, $add)){
                        array_push($del, $v);
                    }
                }
            }

            if(!empty($add)){
                //点位锁定数+1,
                $update_data['incr'] = ['lock_num' => 1];
                $this->Mhouses_points->update_info($update_data, array('in' => array('id' => $add)));
                $this->write_log($data['userInfo']['id'], 1, "{$id}：锁定数+1：".$this->db->last_query());
                //重置这些点位的状态
                $_where['field']['`ad_num`<='] = '`lock_num`+`ad_use_num`';
                $_where['in'] = ['id' => $add];
                $this->Mhouses_points->update_info(['point_status' => 3], $_where);
                $this->write_log($data['userInfo']['id'], 2, "{$id}：更新增加点位状态：".$this->db->last_query());
                unset($update_data, $_where);
            }
            
            if(!empty($del)){
                $del = $this->moveOutReportPoint($del);
                //取消的点位锁定数-1 
                $update_data['decr'] = ['lock_num' => 1];
                $this->Mhouses_points->update_info($update_data, ['in' => array('id' => $del), '`lock_num` >' => 0]);
                $this->write_log($data['userInfo']['id'], 2, "{$id}：锁定数-1：".$this->db->last_query());
                //重置这些点位的状态
                $_where['field']['`ad_num`>'] = '`lock_num`+`ad_use_num`';
                $_where['in'] = ['id' => $del];
                $this->Mhouses_points->update_info(['point_status' => 1], $_where);
                $this->write_log($data['userInfo']['id'], 2, "{$id}：更新减少点位状态：".$this->db->last_query());
            }
            unset($post_data['point_ids_old']);
            unset($post_data['area_id']);
            //重置
            $post_data['point_ids'] = implode(',', $point_ids);
            $result = $this->Mhouses_scheduled_orders->update_info($post_data, array('id' => $id));
            if ($result) {
                $posttemp = [
                    'is_confirm' => 0,
                    'bm_agree' => 0,
                    'mm_agree' => 0,
                ];
                $result = $this->Mhouses_scheduled_orders->update_info($posttemp, array('id' => $id));
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
            $tmp_point_ids = explode(',', $data['info']['point_ids']);
            //如果数据超过1000条，则分批查询
            if(count($tmp_point_ids) > 1000){
                $data['selected_points'] = [];
                $arr = array_chunk($tmp_point_ids, 1000);
                $tmp = [];
                foreach ($arr as $k => $v){
                    $where['in']['A.id'] = $v;
                    $tmp[] = $this->Mhouses_points->get_points_lists($where);
                }
                foreach ($tmp as $k => $v){
                    foreach ($v as $key => $val){
                        $data['selected_points'][] = $val;
                    }
                }
                unset($tmp);
            }else{
                $where['in']['A.id'] = explode(',', $data['info']['point_ids']);
                $data['selected_points'] = $this->Mhouses_points->get_points_lists($where);
            }
            
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
     * 媒介主管审批
     */
    public function mmcheck(){
        $id = $this->input->post('id');
        $status = $this->input->post('status');
        $info = $this->Mhouses_scheduled_orders->get_one('is_confirm', ['id' => $id]);
        if(!$info['is_confirm']) $this->return_json(['msg' => '客户未确认，您不能审核']);
        $res  = $this->Mhouses_scheduled_orders->update_info(['mm_agree' => $status], ['id' => $id]);
        if(!$res) $this->return_json(['msg' => '提交失败']);
        $this->return_json(['msg' => '操作成功']);
    }
    
    /**
     * 业务主管审批
     */
    public function bmcheck(){
        $id = $this->input->post('id');
        $status = $this->input->post('status');
        $info = $this->Mhouses_scheduled_orders->get_one('is_confirm', ['id' => $id]);
        if(!$info['is_confirm']) $this->return_json(['msg' => '客户未确认，您不能审核']);
        $res  = $this->Mhouses_scheduled_orders->update_info(['bm_agree' => $status], ['id' => $id]);
        if(!$res) $this->return_json(['msg' => '提交失败']);
        $this->return_json(['msg' => '操作成功']);
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
        $orderInfo = $this->Mhouses_scheduled_orders->get_one('point_ids,confirm_point_ids,is_confirm,order_status', ['id' => $order_id]);
        if($orderInfo['order_status'] == 5) $this->return_json(['code' => 0, 'msg' => '已转订单，不支持该操作']);
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
        $arr = [
            'confirm_point_ids' => $confirm_point_ids,
            'bm_agree' => 0,
            'mm_agree' => 0,
            'is_confirm' => 0
        ];
        $res = $this->Mhouses_scheduled_orders->update_info($arr, ['id' => $order_id]);
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
        $orderInfo = $this->Mhouses_scheduled_orders->get_one('point_ids,confirm_point_ids,is_confirm,order_status', ['id' => $order_id]);
        if($orderInfo['order_status'] == 5) $this->return_json(['code' => 0, 'msg' => '已转订单，不支持该操作']);
        $confirm_point_ids = '';
        if($status){
            $confirm_point_ids = $orderInfo['point_ids'];
        }
        $arr = [
            'confirm_point_ids' => $confirm_point_ids,
            'bm_agree' => 0,
            'mm_agree' => 0,
            'is_confirm' => 0
        ];
        $res = $this->Mhouses_scheduled_orders->update_info($arr, ['id' => $order_id]);
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
        $orderInfo = $this->Mhouses_scheduled_orders->get_one('point_ids,confirm_point_ids,is_confirm,order_status', ['id' => $order_id]);
        
        if($orderInfo['order_status'] == 5) $this->return_json(['code' => 0, 'msg' => '已转订单，不支持该操作']);
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
        $arr = [
            'confirm_point_ids' => $confirm_point_ids,
            'bm_agree' => 0,
            'mm_agree' => 0,
            'is_confirm' => 0
        ];
        $res = $this->Mhouses_scheduled_orders->update_info($arr, ['id' => $order_id]);
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
        $orderInfo = $this->Mhouses_scheduled_orders->get_one('point_ids,confirm_point_ids,is_confirm,order_status', ['id' => $order_id]);
        if($orderInfo['order_status'] == 5) $this->return_json(['code' => 0, 'msg' => '已转订单，不支持该操作']);
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
        
        $arr = [
            'confirm_point_ids' => $confirm_point_ids,
            'bm_agree' => 0,
            'mm_agree' => 0,
            'is_confirm' => 0
        ];
        $res = $this->Mhouses_scheduled_orders->update_info($arr, ['id' => $order_id]);
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
        if(!empty($this->input->post('houses_id'))) {
            //临时限制不让选花果园和山水黔城
            $houses_id = $this->input->post('houses_id');
            if(in_array($houses_id, [168])){
               $this->return_json(array('flag' => true, 'points_lists' => [], 'count' => 0, 'area_list' => []));
            }else{
               $where['houses_id'] = $houses_id;
            }
        }
        if(!empty($this->input->post('area_id'))) {$where['area_id'] = $this->input->post('area_id');}
        if(!empty($this->input->post('ban'))) $where['ban'] = $this->input->post('ban');
        if(!empty($this->input->post('unit'))) $where['unit'] = $this->input->post('unit');
        if(!empty($this->input->post('floor'))) $where['floor'] = $this->input->post('floor');
        if(!empty($this->input->post('addr'))) $where['addr'] = $this->input->post('addr');
        $lock_start_time = $this->input->post('lock_start_time');
        
        $order_id = $this->input->post('order_id');
        $type = $this->input->post('order_type');
        
        $where['is_del'] = 0;
        $where['`lock_num` >='] = 0; //防止出现多次选择
        $where['point_status'] = 1;
        $where['not_in'] = ['houses_id' => [168]];
        
        $fields = 'id,code,houses_id,area_id,ban,unit,floor,addr,type_id,ad_num, ad_use_num, lock_num,point_status';
        $points_lists = $this->Mhouses_points->get_usable_point($fields, $where, $order_id, $type);
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
    
    /**
     * 撤销预订单
     */
    public function out(){
        $data = $this->data;
        $id = intval($this->input->post('id'));
        $info = $this->Mhouses_scheduled_orders->get_one("*", array('id' => $id, 'is_del' => 0));
        if(!$info) $this->return_json(['code' => 0, 'msg' => '订单不存在']);
        if($info['order_status'] != 1) $this->return_json(['code' => 0, 'msg' => '锁定中的订单才能撤销']);
        //解析点位
        $point_ids = array_unique(explode(',', $info['point_ids']));
        $size = 2000;
        $update = [
            'decr' => ['lock_num' => 1]
        ];
        if(count($point_ids) > $size){
            $arr = array_chunk($point_ids, $size);
            foreach ($arr as $k => $v){
                $this->Mhouses_points->update_info($update, array('`lock_num` >' => 0, 'in' => array('id' => $v)));
                $this->write_log($data['userInfo']['id'] , 1, "id：{$id}-更新点位锁定数".$this->db->last_query());
                //更新点位状态
                $__where['field']['`ad_num`>'] = '`ad_use_num` + `lock_num`';
                $__where['in'] = ['id' => $v];
                $this->Mhouses_points->update_info(['point_status' => 1], $__where);
                $this->write_log($data['userInfo']['id'] , 1, "id：{$id}-更新点位状态".$this->db->last_query());
            }
        }else{
            $this->Mhouses_points->update_info($update, array('`lock_num` >' => 0, 'in' => array('id' => $point_ids)));
            $this->write_log($data['userInfo']['id'] , 1, "id：{$id}-更新点位锁定数".$this->db->last_query());
            //更新点位状态
            $__where['field']['`ad_num`>'] = '`ad_use_num` + `lock_num`';
            $__where['in'] = ['id' => $point_ids];
            $this->Mhouses_points->update_info(['point_status' => 1], $__where);
            $this->write_log($data['userInfo']['id'] , 1, "id：{$id}-更新点位状态".$this->db->last_query());
        }
        
        //删除订单
        $this->Mhouses_scheduled_orders->update_info(['is_del' => 1], ['id' => $id]);
        $this->write_log($data['userInfo']['id'] , 1, "释放预订单{$id}");
        $this->return_json(['code' => 1, 'msg' => '操作成功']);
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
            if($post_data['point_ids']){
                $post_data['point_ids'] = explode(',', $post_data['point_ids']);
                $post_data['point_ids'] = array_unique($post_data['point_ids']);
                $post_data['point_ids'] = implode(',', $post_data['point_ids']);
            }
            
            //检查是否包含异常数据的点位
            $this->checkPointIsCanUse($post_data['point_ids']);
            //查询提交的点位是可用
            $post_data['creator'] =  $data['userInfo']['id'];
            $post_data['create_time'] =  date('Y-m-d H:i:s');
            unset($post_data['houses_id'], $post_data['area_id'],$post_data['ban'],$post_data['unit'],$post_data['floor'],$post_data['addr'], $post_data['hour'], $post_data['minute'], $post_data['second']);
            unset($post_data['point_ids_old']);

            $order_id = $this->Mhouses_orders->create($post_data);
            if ($order_id) {
                
                $where['id'] = $id;
                $info = $this->Mhouses_scheduled_orders->get_one("*", $where);
                //释放该预定订单锁定的所有点位
                $oldpointids = explode(',', $info['point_ids']);
                $size = 2000;
                if(count($oldpointids) > $size){
                    $arr  = array_chunk($oldpointids, $size);
                    foreach ($arr as $k => $v){
                        $update_data['decr'] = ['lock_num' => 1]; //释放点位锁定数
                        $this->Mhouses_points->update_info($update_data, array('`lock_num` >' => 0, 'in' => array('id' => $v)));
                    }
                }else{
                    $update_data['decr'] = ['lock_num' => 1]; //释放点位锁定数
                    $this->Mhouses_points->update_info($update_data, array('`lock_num` >' => 0, 'in' => array('id' => $oldpointids)));
                }
                
                unset($update_data['decr']);
                //字段的比较where['field'],将所有预定的点位释放掉
                $__where['field']['`ad_num`>'] = '`ad_use_num` + `lock_num`';
                $__where['in'] = ['id' => $oldpointids];
                $this->Mhouses_points->update_info(['point_status' => 1], $__where);
                
                //更新该订单的状态为“已转订单”
                $this->Mhouses_scheduled_orders->update_info(array('order_status' => 5), array('id' => $info['id']));
                
                //下单成功把选择的点增加占用客户，和增加上画次数
                $update_data['joint']['`customer_id`'] = ','.$post_data['customer_id'];
                //增加投放总量，一天为一次
                $update_data['incr']['used_num'] = ceil( ( strtotime($post_data['release_end_time']) - strtotime($post_data['release_start_time'])) / (24*3600) );
                //增加点位可使用量+1次，表示该点位少一次可放。
                $update_data['incr']['`ad_use_num`'] = 1;
                $this->Mhouses_points->update_info($update_data, array('in' => array('id' => explode(',', $post_data['point_ids']))));

                //更新点位状态
                $_where = [];
                $_where['in'] = array('id' => explode(',', $post_data['point_ids']));
                //字段的比较where['field']
                $_where['field']['`ad_num`<='] = '`ad_use_num` + `lock_num`';
                $this->Mhouses_points->update_info(['point_status' => 3], $_where);
                
                //更新点位order_id
                $this->addOrderIdToPoint($order_id, explode(',', $post_data['point_ids']));
                
                
                //判断是否有自有画面的点位，如果有，则将自有订单的点位去除，并更新self_lock = 0
                $selfOrderList =  $this->Mhouses_orders->get_lists('id, point_ids', ['is_self' => 1, 'order_status <' => 8]);
                
                if($selfOrderList){
                    //转订单的点位
                    $ids = explode(',', $post_data['point_ids']);
                    foreach ($selfOrderList as $k => $v){
                        if(!empty($v['point_ids'])){
                            $out = [];
                            //自有订单的点位
                            $tmp = explode(',', $v['point_ids']);
                            foreach ($tmp as $k1 => $v1){
                                if(in_array($v1, $ids)){
                                    $out[] = $v1;
                                    unset($tmp[$k1]);
                                }
                            }
                            //释放自有点位
                            if(count($out) > 0){
                                $this->Mhouses_points->update_info(['self_lock' => 0], ['in' => ['id' => $out]]);
                                //更新本次的自有订单点位订单
                                if(count($tmp) > 0){
                                    $this->Mhouses_orders->update_info(['point_ids' => implode(',', $tmp)], ['id' => $v['id']]);
                                }else{
                                    $this->Mhouses_orders->update_info(['point_ids' => ''], ['id' => $v['id']]);
                                }
                            }
                        }
                    }
                }
                
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
    
    /**
     * 转订单时，点位的order_id加上本订单的id
     * @param number $order_id
     * @param array $point_ids
     */
    private function addOrderIdToPoint($order_id = 0, $point_ids = []){
        $list = [];
        $where['in'] = ['id' => $point_ids];
        $point_list = $this->Mhouses_points->get_lists('id, order_id', $where);
        if($point_list){
            foreach ($point_list as $k => $v){
                $list[$k]['id'] = $v['id'];
                if(empty($v['order_id'])){
                    $list[$k]['order_id'] = $order_id;
                }else{
                    $now = [];
                    $tmp = explode(',', $v['order_id']);
                    foreach ($tmp as $key => $val){
                        if($val){
                            array_push($now, $val);
                        }
                    }
                    if(empty($now)){
                        $list[$k]['order_id'] = $order_id;
                    }else{
                        array_push($now, $order_id);
                        $list[$k]['order_id'] = implode(',', $now);
                    }
                }
                
            }
        }
        //数据准备完毕
        $sql = "update t_houses_points SET order_id = CASE id";
        foreach ($list as $k => $v){
            $sql.= " WHEN {$v['id']} THEN '{$v['order_id']}'";
        }
        $sql .= " END where id in(";
        $sql .= implode(',', $point_ids);
        $sql .= ')';
        $this->db->query($sql);
        
        return $this->db->count_all_results();
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
    
    /**
     * 转订单前检查所选的点位是否可用
     * @param string $point_ids
     */
    public function checkPointIsCanUse($point_ids = ''){
        $data = $this->data;
        $point_ids = explode(',', $point_ids);
        $pointList = $this->Mhouses_points->get_lists('id,code,point_status,type_id,ad_num,ad_use_num,lock_num', ['in' => ['id' => $point_ids]]);
        $msg = '';
        if($pointList){
            foreach ($pointList as $k => $v){
                if($v['point_status'] == 4){
                    $this->write_log($data['userInfo'], 4, '转订单包含已报损点位 编号：'.$v['code']);
                    $msg = '包含已报损点位，无法转订单，请管理员解决';
                }
                if($v['point_status'] == 1 && $v['type_id'] == 1){
                    $msg = '包含空闲点位，无法转订单，请重新编辑';
                    break;
                }
                if($v['ad_num'] < ($v['ad_use_num'] + $v['lock_num'])){
                    $msg = '包含异常数据点位，无法转订单，请管理员解决';
                    break;
                }
                if($v['ad_use_num'] > 0 && $v['type_id'] == 1){
                    $this->write_log($data['userInfo'], 4, '转订单包含已上画的点位编号：'.$v['code']);
                    $msg = '包含已上画点位，无法转订单，请管理员解决';
                    break;
                }
            }
        }
        if(!empty($msg)) $this->backAlert($msg);
    }
    
    private function backAlert($msg = ''){
        $this->error($msg);
    }
    
    /**
     * 移除、排除已报损不可上画的点位
     * @param array $ids
     */
    private function moveOutReportPoint($ids=[]){
        $where['repair_time'] = 0;
        $where['usable'] = 0;//是否可以上画
        $list = $this->Mhouses_points_report->get_lists('point_id', $where, ['create_time' => 'desc'], 0, 0, ['point_id']);
        if(count($list)==0){
            return $ids;
        }
        //提取点位ids
        $rpoint_ids = array_column($list, 'point_id');
        foreach ($ids as $k => $v){
            if(in_array($v, $rpoint_ids)){
                unset($ids[$k]);
            }
        }
        return $ids;
    }
}