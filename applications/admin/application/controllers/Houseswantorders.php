<?php 
/**
* 意向订单管理控制器
* @author yangxiong 867332352@qq.com
*/

defined('BASEPATH') or exit('No direct script access allowed');
class Houseswantorders extends MY_Controller{

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
        $this->data['active'] = 'houseswantorders_list';
        
        $this->data['customers'] = $this->Mhouses_customers->get_lists("id, name", array('is_del' => 0));  //客户
        $this->data['make_company'] = $this->Mmake_company->get_lists('id, company_name, business_scope', array('is_del' => 0));  //制作公司
        $this->data['order_type_text'] = C('order.houses_order_type'); //订单类型
        $this->data['salesman'] = $this->Msalesman->get_lists('id, name, sex, phone_number', array('is_del' => 0));  //业务员
        $this->data['point_addr'] = C('housespoint.point_addr');	//点位位置
    }
    
    /**
     * 意向订单首页
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
        
        $this->load->view('houseswantorders/index', $data);
    }
    
    /**
     * 添加意向订单
     * @param number $order_type
     */
    public function add($order_type=1, $put_trade=0){
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
            $post_data['confirm_point_ids'] = '';
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
        //end
        //获取所有业务员
        $data['yewu'] = $this->Madmins->get_lists('id, fullname', array('group_id' => 2,'is_del' => 1));
        $this->load->view('houseswantorders/add', $data);
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
     * 根据条件获取点位的列表和数量
     */
    public function get_points() {

        if($this->input->post('order_type')) $where['A.type_id'] = $this->input->post('order_type');
        if(!empty($this->input->post('province'))) $where['B.province'] = $this->input->post('province');
        if(!empty($this->input->post('city'))) $where['B.city'] = $this->input->post('city');
        if(!empty($this->input->post('area'))) $where['B.area'] = $this->input->post('area');
        if(!empty($this->input->post('houses_type'))) $where['B.type'] = $this->input->post('houses_type');
        //if(!empty($this->input->post('begin_year'))) $where['B.deliver_year<='] = $this->input->post('begin_year');
        //if(!empty($this->input->post('end_year'))) $where['B.deliver_year>='] = $this->input->post('end_year');
        //if(!empty($this->input->post('put_trade'))) $where['B.put_trade'] = $this->input->post('put_trade');
        
        //$where['A.is_del'] =  0;
        
        $points_lists = $this->Mhouses_points->get_points_lists($where);
        
        if(count($points_lists) > 0) {
        	array_column($points_lists, 'last_name');
            
        }
       
        $this->return_json(array('flag' => true, 'points_lists' => $points_lists, 'count' => count($points_lists)));
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
                        //$update_data['lock_customer_id'] = $update_data['lock_start_time'] = $update_data['lock_end_time'] = $update_data['expire_time'] = '';
                    	$update_data['lock_customer_id'] = 0;
                    	$update_data['is_lock'] = 0;
                        $this->Mhouses_points->update_info($update_data, array('in' => array('id' => explode(',', $info['point_ids']))));

                        //更新该订单的状态为“已释放”
                        $this->Mhouses_scheduled_orders->update_info(array('order_status' => 5), array('id' => $info['id']));
                    }

                    //下单成功把选择的点位置为占用状态(只针对公交灯箱和户外高杆)
                    $update_data['order_id'] = $id;
                    $update_data['customer_id'] = $post_data['customer_id'];
//                     $update_data['lock_start_time'] = '';
//                     $update_data['lock_end_time'] = '';
//                     $update_data['expire_time'] = '';
                    $update_data['point_status'] = 3;
                    $this->Mhouses_points->update_info($update_data, array('in' => array('id' => explode(',', $post_data['point_ids']))));

//                 } 

                $this->write_log($data['userInfo']['id'], 1, "社区资源管理转预定订单".$data['order_type_text'][$post_data['order_type']]."为订单,订单id【".$id."】");
                $this->success("预定订单转订单成功！","/housesorders");
            } else {
                $this->success("预定订单转订单失败！","/housesorders");
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
     * 给业务员发送短信
     * @author yonghua 254274509@qq.com
     */
    public function sendMsg(){

        $sales_id = intval($this->input->post('sales_id'));
        $info = $this->Msalesman->get_one('phone_number, name', ['id' => $sales_id]);
        if(!$info) $this->return_json(['code' => 0, 'msg' => '业务员不存在']);
        if(empty($info['phone_number'])){
            $this->return_json(['code' => 0, 'msg' => '电话不能为空！']);
        }
        if(!preg_match('/^1[3|4|5|8|7][0-9]\d{8}$/', $info['phone_number'])){
            $this->return_json(['code' => 0, 'msg' => '业务员手机号格式不正确！']);
        }
        // 配置短信信息
        $app = C('sms.app');
        $parems = [
            'PhoneNumbers' => $info['phone_number'],
            'SignName' => C('sms.sign.lkcb'),
            'TemplateCode' => C('sms.template.yewuyuan'),
            'TemplateParam' => array(
                'name' => $info['name']
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
    
}