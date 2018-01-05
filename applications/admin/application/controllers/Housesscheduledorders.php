<?php 
/**
* 预定订单管理控制器
* @author yonghua 254274509@qq.com
*/
use YYHSms\SendSms;

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
        if ($this->input->get('customer_id')) $where['A.lock_customer_id'] = $this->input->get('customer_id');
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
        $data['confirm_text'] = C('housesscheduledorder.customer_status');
        
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
            unset($post_data['ban'], $post_data['unit'], $post_data['floor']);
            if (isset($post_data['area_id'])) unset($post_data['area_id']);
            if (isset($post_data['addr'])) unset($post_data['addr']);
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
            $where['order_status'] = C('housesscheduledorder.order_status.code.done_release');
            $where['lock_end_time >'] = date('Y-m-d');
            $orderinfo = $this->Mhouses_scheduled_orders->get_one('*', $where);
            if ($orderinfo) {
                $this->success("该客户上一次释放的订单还未到锁定结束日期，不能新建预定订单！", '/housesscheduledorders/addpreorder/'.$order_type);
                exit;
            }
            
            $post_data['create_user'] = $post_data['update_user'] = $data['userInfo']['id'];
            $post_data['create_time'] = $post_data['update_time'] = date('Y-m-d H:i:s');
            $post_data['point_ids'] = implode(',', array_unique(explode(',', $post_data['point_ids'])));
            $id = $this->Mhouses_scheduled_orders->create($post_data);
            if ($id) {
                //更新点位的lock_customer_id和状态和is_lock
                $update_data['lock_customer_id'] = $post_data['lock_customer_id'];
                $update_data['is_lock'] = 1;
                $this->Mhouses_points->update_info($update_data, array('in' => array('id' => explode(',', $post_data['point_ids']))));
                
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
       
        $data['housesList'] = $this->Mhouses->get_lists("id, name", ['is_del' => 0]);

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
            if (isset($post_data['addr'])) unset($post_data['addr']);
            unset($post_data['id'], $post_data['ban'], $post_data['unit'], $post_data['floor']);
            //先把之前所有已选择的点位的状态置为未锁定，再把重新选择的点位状态置为锁定
            //此处要求最好锁表，以免刚释放的点位被他人占用
            //禁止其他人写入
            $this->db->query('lock table t_houses_points read');
            $this->db->query('lock table t_houses_points write');
            $this->Mhouses_points->update_info(
                array(
                    'is_lock' => '0',
                    'lock_customer_id' => '0',
                ),
                array(
                    'in' => array(
                        'id' => explode(',', $post_data['point_ids_old'])
                    )
                )
            );
            $update_data['lock_customer_id'] = $post_data['lock_customer_id'];
            $update_data['is_lock'] = 1;
            $this->Mhouses_points->update_info($update_data, array('in' => array('id' => explode(',', $post_data['point_ids']))));
            //释放
            $this->db->query('unlock table');
            unset($post_data['point_ids_old']);
            unset($post_data['area_id']);
            $post_data['point_ids'] = implode(',', array_unique(explode(',', $post_data['point_ids'])));
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
            
            //组团列表
            $data['houses_list'] = $this->Mhouses->get_lists("id, name", array('is_del' => 0));
            
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
        if($tab) $data['tab'] = 'point';
        
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

        $this->load->view('housesscheduledorders/detail', $data);
    }
    
    /**
     * 根据条件获取点位的列表和数量
     */
    public function get_points() {

        if($this->input->post('order_type')) $where['type_id'] = $this->input->post('order_type');
        if(!empty($this->input->post('houses_id'))) $where['houses_id'] = $this->input->post('houses_id');
        if(!empty($this->input->post('ban'))) $where['ban'] = $this->input->post('ban');
        if(!empty($this->input->post('unit'))) $where['unit'] = $this->input->post('unit');
        if(!empty($this->input->post('floor'))) $where['floor'] = $this->input->post('floor');
        if(!empty($this->input->post('addr'))) $where['addr'] = $this->input->post('addr');
        $lock_start_time = $this->input->post('lock_start_time');
        
        $where['is_del'] = $where['is_lock'] = 0;
        $fields = 'id,code,houses_id,is_lock,area_id,ban,unit,floor,addr,type_id,point_status';
        $points_lists = $this->Mhouses_points->get_usable_point($fields, $where, $lock_start_time);
        if(count($points_lists) > 0) {
            $housesid = array_unique(array_column($points_lists, 'houses_id'));
            $area_id = array_unique(array_column($points_lists, 'area_id'));
            $housesList = $this->Mhouses->get_lists("id, name,", ['in' => ['id' => $housesid]]);            
            $wherea['in']['id'] = $area_id;
            $areaList = $this->Mhouses_area->get_lists("id, name", $wherea);
            //获取规格列表
            $size_list = $this->Mhouses_points_format->get_lists('id,type,size', ['is_del' => 0]);
            foreach ($points_lists as $k => &$v) {
                //设置状态
                $v['point_status_txt'] = C('public.points_status')[$v['point_status']];
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
        $areaList = array_unique(array_column($points_lists, 'area_name'));
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
            '所属组团' => "houses_name",
            '所属区域' => "houses_area_name",
            '详细地址' => "addr",
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
        
        $where['in']['A.id'] = explode(',', $scheduledorder['point_ids']);
        
        $customers = $this->Mhouses_customers->get_one("name", array('id' => $scheduledorder['lock_customer_id'], 'is_del' => 0)); //客户
        
        $list = $this->Mhouses_points->get_points_lists($where);
                
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
        $objWriter->save('php://output');
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
     * 给客户发送短信确认点位
     * @author yonghua 254274509@qq.com
     */
    public function sendMsg(){
        //根据预定订单获取客户电话
        $orderid = intval($this->input->post('order_id'));
        $customer_id = intval($this->input->post('customer_id'));
        $info = $this->Mhouses_customers->get_one('contact_tel', ['id' => $customer_id]);
        if(!$info) $this->return_json(['code' => 0, 'msg' => '客户不存在']);
        if(empty($info['contact_tel'])){
            $this->return_json(['code' => 0, 'msg' => '电话不能为空！']);
        }
        if(!preg_match('/^1[3|4|5|8|7][0-9]\d{8}$/', $info['contact_tel'])){
            $this->return_json(['code' => 0, 'msg' => '客户手机号格式不正确！']);
        }
        //生成token
        $token = encrypt(['id' => $orderid]);
        // 配置短信信息
        $app = C('sms.app');
        $parems = [
            'PhoneNumbers' => $info['contact_tel'],
            'SignName' => C('sms.sign.tgkj'),
            'TemplateCode' => C('sms.template.keihu'),
            'TemplateParam' => array(
                'token' => $token
            )
        ];
        //发送短信
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
}