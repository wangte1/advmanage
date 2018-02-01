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
            'Model_houses_want_orders' => 'Mhouses_want_orders',
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
        $this->data['put_trade'] = C('housespoint.put_trade'); //禁投放行业
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
        if ($this->input->get('province')) $where['province'] = $this->input->get('province');
        if ($this->input->get('city')) $where['city'] = $this->input->get('city');
        if ($this->input->get('area')) $where['area'] = $this->input->get('area');
        if ($this->input->get('customer_id')) $where['customer_id'] = $this->input->get('customer_id');
        if ($this->input->get('put_trade')) $where['put_trade'] = $this->input->get('put_trade');
        if ($this->input->get('status')) $where['status'] = $this->input->get('status');
        
        $data['province'] = $this->input->get('province');
        $data['city'] = $this->input->get('city');
        $data['area'] = $this->input->get('area');
        $data['customer_id'] = $data['customer_id']= $this->input->get('customer_id');
        $data['put_trade'] = $this->input->get('put_trade');
        $data['status'] = $this->input->get('status');
        
        $data['list'] = $this->Mhouses_want_orders->get_lists('*', $where, ($page-1)*$pageconfig['per_page'], $pageconfig['per_page']);
        $data_count = $this->Mhouses_want_orders->count($where);
        $data['data_count'] = $data_count;
        $data['page'] = $page;
        
        //var_dump($data['list']);
        
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
            $post_data['create_user'] = $data['userInfo']['id'];
            $post_data['create_time'] = date('Y-m-d H:i:s');
            $id = $this->Mhouses_want_orders->create($post_data);
            if ($id) {
                $this->write_log($data['userInfo']['id'], 1, "新增意向订单,订单id【".$id."】");
                $this->success("添加成功！","/houseswantorders");
            } else {
                $this->success("添加失败！");
            }
        }
       
        $data['status_text'] = C('order.order_status.text');
        
        //end
        //获取所有业务员
        $data['yewu'] = $this->Madmins->get_lists('id, fullname', array('group_id' => 2,'is_del' => 1));
        $this->load->view('houseswantorders/add', $data);
    }
    
    /**
     * 订单详情
     */
    public function detail($id) {
    	$data = $this->data;
    	$data['info'] = $this->Mhouses_want_orders->get_one('*', ['id'=>$id]);
    	
    	$this->load->view("houseswantorders/detail", $data);
    }
    
    
    /**
     * 意向订单转预定订单
     */
    public function checkout($id) {
    	$data = $this->data;
    	$data['info'] = $this->Mhouses_want_orders->get_one('*', ['id'=>$id]);
    	
    	$this->load->view("houseswantorders/checkout", $data);
    }
    
    
    /**
     * 根据模糊条件获取楼盘信息
     */
    public function get_houses() {

        if($this->input->post('order_type')) $where['A.type_id'] = $this->input->post('order_type');
        if(!empty($this->input->post('province'))) $where['B.province'] = $this->input->post('province');
        if(!empty($this->input->post('city'))) $where['B.city'] = $this->input->post('city');
        if(!empty($this->input->post('area'))) $where['B.area'] = $this->input->post('area');
        
        if(!empty($this->input->post('houses_type'))) {
        	 
        	$tmp_type_arr = explode(",", $this->input->post('houses_type'));
        	 
        	$where['in']['B.type'] = $tmp_type_arr;
        }
        if(!empty($this->input->post('begin_year'))) $where['B.deliver_year>='] = $this->input->post('begin_year');
        if(!empty($this->input->post('end_year'))) $where['B.deliver_year<='] = $this->input->post('end_year');
        if(!empty($this->input->post('put_trade'))) $put_trade = $this->input->post('put_trade');
       	
        $where['A.point_status'] = 1;
        
        $points_lists = $this->Mhouses_points->get_points_lists($where);
        $houses_lists = [];
        if(count($points_lists) > 0) {
        	$tmp_arr = array_column($points_lists, 'houses_name', 'houses_id');
        	$tmp_arr1 = [];
        	
        	foreach($points_lists as $k => $v) {
        		$mark = false;
        		if(!empty($put_trade)) {
        			if(in_array($put_trade, explode(",", $v['put_trade']))) {
        				unset($tmp_arr[$v['houses_id']]);
        				unset($points_lists[$k]);
        				$mark = true;
        			}
        		}
        		
        		if($mark == false) {
        			foreach($tmp_arr as $k1 => &$v1) {
        				if($k1 == $v['houses_id']) {
        					if(!isset($tmp_arr1[$k1])) {
        						$tmp_arr1[$k1] = 0;
        					}
        					$tmp_arr1[$k1] = $tmp_arr1[$k1] + ($v['ad_num']-$v['ad_use_num']);
        				}
        			}
        		}
        		
        	}
        	
        	$i = 0;
        	foreach($tmp_arr as $k => $v) {
        		$houses_lists[$i]['houses_name'] = $v;
        		if(isset($tmp_arr1[$k])) {
        			$houses_lists[$i]['count'] = $tmp_arr1[$k];
        		}
        		
        		$i++;
        	}
        }
       
        $this->return_json(array('flag' => true, 'houses_lists' => $houses_lists, 'count' => array_sum($tmp_arr1)));
    }
    
    /*
     * 获取点位列表
     */
    public function get_points() {
    	
    	if($this->input->post('order_type')) $where['A.type_id'] = $this->input->post('order_type');
    	if(!empty($this->input->post('province'))) $where['B.province'] = $this->input->post('province');
    	if(!empty($this->input->post('city'))) $where['B.city'] = $this->input->post('city');
    	if(!empty($this->input->post('area'))) $where['B.area'] = $this->input->post('area');
    	
    	if(!empty($this->input->post('houses_type'))) {
    	
    		$tmp_type_arr = explode(",", $this->input->post('houses_type'));
    	
    		$where['in']['B.type'] = $tmp_type_arr;
    	}
    	if(!empty($this->input->post('begin_year'))) $where['B.deliver_year>='] = $this->input->post('begin_year');
    	if(!empty($this->input->post('end_year'))) $where['B.deliver_year<='] = $this->input->post('end_year');
    	if(!empty($this->input->post('put_trade'))) $put_trade = $this->input->post('put_trade');
    	
    	if(!empty($this->input->post('houses_id'))) $where['A.houses_id'] = $this->input->post('houses_id');
    	if(!empty($this->input->post('area_id'))) $where['A.area_id'] = $this->input->post('area_id');
    	if(!empty($this->input->post('ban'))) $where['A.ban'] = $this->input->post('ban');
    	if(!empty($this->input->post('unit'))) $where['A.unit'] = $this->input->post('unit');
    	if(!empty($this->input->post('floor'))) $where['A.floor'] = $this->input->post('floor');
    	if(!empty($this->input->post('addr'))) $where['A.addr'] = $this->input->post('addr');
    	
    	$where['A.point_status'] = 1;
    	
    	$points_lists = $this->Mhouses_points->get_points_lists($where);
    	
    	$point_count = 0;
    	
    	if(count($points_lists) > 0) {
    		$houses_lists = array_column($points_lists, 'houses_name', 'houses_id');
    		$area_lists = array_column($points_lists, 'houses_area_name', 'area_id');
    		$ban_lists = array_column($points_lists, 'ban');
    		$unit_lists = array_column($points_lists, 'unit');
    		$floor_lists = array_column($points_lists, 'floor');
    		$addr_lists = array_column($points_lists, 'addr');
    		
	    	foreach($points_lists as $k => &$v) {
	    		$point_count = $point_count + ($v['ad_num'] - $v['ad_use_num']);
	    		$v['point_status_txt'] = C('housespoint.points_status')[$v['point_status']];
	    	}
    	}
    	
    	$this->return_json(array(
    			'flag' => true,
    			'points_lists' => $points_lists,
    			'houses_lists'=>$houses_lists,
    			'area_lists'=>$area_lists,
    			'ban_lists'=>$ban_lists,
    			'unit_lists'=>$unit_lists,
    			'floor_lists'=>$floor_lists,
    			'addr_lists'=>$addr_lists,
    			'count' => $point_count
    	));
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