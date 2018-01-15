<?php 
/**
* 换画订单控制器
* @author jianming
*/
defined('BASEPATH') or exit('No direct script access allowed');
class Houseschangepicorders extends MY_Controller{

    public function __construct(){
        parent::__construct();
        $this->load->model([
             'Model_houses_orders' => 'Mhouses_orders',
             'Model_houses_change_pic_orders' => 'Mhouses_changepicorders',
             //'Model_medias' => 'Mmedias',
             'Model_houses_customers' => 'Mhouses_customers',
             'Model_admins' => 'Madmins',
             'Model_houses_points' => 'Mhouses_points',
             'Model_make_company' => 'Mmake_company',
             'Model_status_operate_time' => 'Mstatus_operate_time',
             'Model_inspect_images' => 'Minspect_images',
             'Model_houses_order_inspect_images' => 'Mhouses_order_inspect_images',
             'Model_salesman' => 'Msalesman',
        	 'Model_houses_assign_down' => 'Mhouses_assign_down',
             //'Model_points_make_num' => 'Mpoints_make_num',
        ]);
        $this->data['code'] = 'horders_manage';
        $this->data['active'] = 'houses_change_pic_order_list';

        //$this->data['medias'] = $this->Mmedias->get_lists("id, code, name", array('is_del' => 0));  //站台
        $this->data['customers'] = $this->Mhouses_customers->get_lists("id, name", array('is_del' => 0));  //客户
        $this->data['make_company'] = $this->Mmake_company->get_lists('id, company_name, business_scope', array('is_del' => 0));  //制作公司
        $this->data['order_type_text'] = C('housesorder.houses_order_type'); //订单类型
        $this->data['houses_assign_status'] = C('housesorder.houses_assign_status'); //派单状态
    }
    

    /**
     * 换画订单列表
     */
    public function index() {
        $data = $this->data;
        $pageconfig = C('page.page_lists');
        $this->load->library('pagination');
        $page = $this->input->get_post('per_page') ? : '1';

        $where =  array();
        if ($this->input->get('order_code')) $where['A.order_code'] = $this->input->get('order_code');
        if ($this->input->get('order_type')) $where['B.order_type'] = $this->input->get('order_type');
        if ($this->input->get('customer_id')) $where['B.customer_id'] = $this->input->get('customer_id');
        if ($this->input->get('order_status')) $where['A.order_status'] = $this->input->get('order_status');

        $data['order_code'] = $this->input->get('order_code');
        $data['order_type'] = $this->input->get('order_type');
        $data['customer_id'] = $this->input->get('customer_id');
        $data['order_status'] = $this->input->get('order_status');

        $data['list'] = $this->Mhouses_changepicorders->get_order_lists($where, ($page-1)*$pageconfig['per_page'], $pageconfig['per_page']);
        $data_count = $this->Mhouses_changepicorders->get_order_count($where);
        
        $data['data_count'] = $data_count;
        $data['page'] = $page;

        //获取分页
        $pageconfig['base_url'] = "/houseschangepicorders";
        $pageconfig['total_rows'] = $data_count;
        $this->pagination->initialize($pageconfig);
        $data['pagestr'] = $this->pagination->create_links(); // 分页信息

        $admins = $this->Madmins->get_lists("id,name");
        $data['admins'] = array_column($admins,"name","id");
        $data['status_text'] = C('housesorder.houses_order_status.text');
        $this->load->view("housesorders/change_pic/index", $data);
    }


    /**
     * 新建换画订单
     */
    public function add($order_type, $order_code = 0) {
        $data = $this->data;
        if (IS_POST) {
            $post_data = $this->input->post();
            
            if (isset($post_data['make_complete_time'])) {
                $post_data['make_complete_time'] = $post_data['make_complete_time'].' '.$post_data['hour'].':'.$post_data['minute'].':'.$post_data['second'];
            }

            $post_data['creator'] = $post_data['update_user'] = $data['userInfo']['id'];
            $post_data['create_time'] = $post_data['update_time'] = date('Y-m-d H:i:s');
            unset($post_data['order_type'], $post_data['media_id'], $post_data['make_num'], $post_data['hour'], $post_data['minute'], $post_data['second']);
            $id = $this->Mhouses_changepicorders->create($post_data);
            if ($id) {

                $this->write_log($data['userInfo']['id'], 1, "新增".$data['order_type_text'][$order_type]."换画订单,订单id【".$id."】");
                
                $this->success("添加成功！","/houseschangepicorders");
            } else {
                $this->success("添加失败！","/houseschangepicorders");
            }
        } else {
            $data['order_type'] = $order_type;

            if ($order_code > 0) $data['order_code'] = $order_code;
            $this->load->view("housesorders/change_pic/add", $data);
        }
    }


    /**
     * 选择换画类型
     */
    public function order_type() {
        $data = $this->data;
        $this->load->view('housesorders/change_pic/order_type', $data);
    }


    /**
     * 获取订单信息和投放点位列表和数量
     */
    public function get_points() {
        $where = array(
            'A.order_code' => $this->input->post('order_code'), 
            'A.order_type' => $this->input->post('order_type'),
            'A.order_status' => 7
        );

        //订单信息
        $order = $this->Mhouses_orders->get_order_lists($where)[0];

        $where_point['in']['A.id'] = explode(',', $order['point_ids']);
        $points_lists = $this->Mhouses_points->get_points_lists($where_point);
//         if ($points_lists) {
//             $make_num = $this->Mpoints_make_num->get_lists('order_id, point_id, make_num', array('order_id' => $order['id'], 'type' => 1));
//             foreach ($points_lists as $key => $value) {
//                 foreach ($make_num as $k => $v) {
//                     if ($v['point_id'] == $value['id']) {
//                         $points_lists[$key]['make_num'] = $v['make_num'];
//                     }
//                 }
//             }
//         }
        $order['order_type'] = $this->data['order_type_text'][$order['order_type']];
        $this->return_json(array('flag' => true, 'order_info' => $order, 'points_lists' => $points_lists, 'count' => count($points_lists)));
    }



    /* 
     * 编辑换画订单
     */
    public function edit($id) {
        $data = $this->data;
        if (IS_POST) {
            $post_data = $this->input->post();

            if (isset($post_data['make_complete_time'])) {
                $post_data['make_complete_time'] = $post_data['make_complete_time'].' '.$post_data['hour'].':'.$post_data['minute'].':'.$post_data['second'];
            }
            
            $post_data['update_user'] = $data['userInfo']['id'];
            $post_data['update_time'] = date('Y-m-d H:i:s');

            //先清空点位制作张数表t_points_make_num，再添加进去
//             if ($post_data['order_type'] == 1 || $post_data['order_type'] == 2) {
//                 $this->Mpoints_make_num->delete(array('order_id' => $id, 'type' => 2));
//                 foreach ($post_data['make_num'] as $key => $value) {
//                     $make_num_data['order_id'] = $id;
//                     $make_num_data['point_id'] = $key;
//                     $make_num_data['make_num'] = $value;
//                     $make_num_data['type'] = 2;
//                     $this->Mpoints_make_num->create($make_num_data);
//                 }
//             }

            unset($post_data['order_type'], $post_data['make_num'], $post_data['make_num'], $post_data['hour'], $post_data['minute'], $post_data['second']);
            $id = $this->Mhouses_changepicorders->update_info($post_data, array('id' => $id));
            if ($id) {

                $this->success("修改成功！","/changepicorders");
            } else {
                $this->success("修改失败！请重试！","/changepicorders");
            }
        } else {
            $data['info'] = $this->Mhouses_changepicorders->get_one("*", array('id' => $id));

            //订单信息
            $data['order'] = $this->Mhouses_orders->get_order_lists(array('A.order_code' => $data['info']['order_code']))[0];
            $data['order_type'] = $data['order']['order_type'];

            $order_point = explode(',', $data['order']['point_ids']);
            $change_point = explode(',', $data['info']['point_ids']);

            $data['point_count'] = count($order_point);
            $point_arr = array_diff($order_point, $change_point);

            //供选择的点位列表
            if($point_arr) {
                $data['points_lists'] = $this->Mhouses_points->get_points_lists(array('in' => array('A.id' => $point_arr)));
            } else {
                $data['points_lists'] = array();
            }

            //已选择的点位列表
            $data['selected_points'] = $this->Mhouses_points->get_points_lists(array('in' => array('A.id' => $change_point)));

            if ($data['order_type'] == 1 || $data['order_type'] == 2) {
                //主订单点位制作张数（灯箱和高杆）
//                 $data['points_make_num'] = $this->Mpoints_make_num->get_lists('order_id, point_id, make_num', array('order_id' => $data['order']['id'], 'type' => 1));
//                 foreach ($data['points_lists'] as $key => $value) {
//                     foreach ($data['points_make_num'] as $k => $v) {
//                         if ($value['id'] == $v['point_id']) {
//                             $data['points_lists'][$key]['make_num'] = $data['points_make_num'][$k]['make_num'];
//                         }
//                     }
//                 }

                //已选择换画点位制作张数（灯箱和高杆）
//                 $data['points_make_num'] = $this->Mpoints_make_num->get_lists('order_id, point_id, make_num', array('order_id' => $data['info']['id'], 'type' => 2));
//                 foreach ($data['selected_points'] as $key => $value) {
//                     foreach ($data['points_make_num'] as $k => $v) {
//                         if ($value['id'] == $v['point_id']) {
//                             $data['selected_points'][$key]['make_num'] = $data['points_make_num'][$k]['make_num'];
//                         }
//                     }
//                 }
            }
            $this->load->view("housesorders/change_pic/add", $data);
        }
    }


    /**
     * 生成联系单
     */
    public function contact_list($id) {
        $data = $this->data;
        $data['info'] = $this->Mhouses_changepicorders->get_one("*", array('id' => $id));
        $order = $this->Mhouses_orders->get_one('*', array('order_code' => $data['info']['order_code']));

        //甲方负责人
        $admin = $this->Madmins->get_one('fullname, tel', array('id' => $data['info']['create_user']));
        $data['info']['A_contact_man'] = $admin['fullname'];
        $data['info']['A_contact_mobile'] = $admin['tel'];

        //客户名称
        $data['info']['customer_name'] = $this->Mhouses_customers->get_one('name', array('id' => $order['customer_id']))['name'];

        $data['info']['release_start_time'] = $order['release_start_time'];
        $data['info']['release_end_time'] = $order['release_end_time'];
        $data['info']['is_change_pic'] = 1;
        $data['info']['order_type'] = $order['order_type'];
        

        if ($data['info']['order_type'] == 1 || $data['info']['order_type'] == 2) {
            //制作单位
            $make_company = $this->Mmake_company->get_one('*', array('id' => $data['info']['make_company_id']));
            $data['info']['make_company'] = $make_company['company_name'];
            $data['info']['seal_img'] = $make_company['seal_img'];
            $data['info']['contact_man'] = $make_company['contact_man']; //乙方联系人
            $data['info']['contact_mobile'] = $make_company['contact_mobile']; //乙方电话

            //制作数量
            $make_info = $this->get_make_info($data['info']);
            $data['make_num'] = $make_info['make_num'];
            $data['total_counts'] = $make_info['total_counts'];
            $data['high_count'] = $make_info['high_count'];
            $data['total_num'] = $make_info['total_num'];

        	if ($data['info']['order_type'] == '1') {    //冷光灯箱
                $this->load->view('housesorders/contact_list/light', $data);
            } elseif ($data['info']['order_type'] == '2') {   //广告机
                //$data['media_list'] = $this->Mhouses_points->get_make_high(array('in' => array('B.id' => explode(',', $data['info']['point_ids']))));
                //$this->load->view('housesorders/contact_list/high', $data);
            	$this->load->view('housesorders/contact_list/light', $data);
            }

        }

    }


    /**
     * 生成确认函
     */
    public function confirmation($id) {
        $data = $this->data;
        $data['info'] = $this->Mhouses_changepicorders->get_one("*", array('id' => $id));

        $images = $this->Mhouses_order_inspect_images->get_lists("*", array('order_id' => $id, 'type' => 2));
        if (!$images) {
            $this->success("请先上传验收图片！","/houseschangepicorders");
        }

        $order = $this->Mhouses_orders->get_one('*', array('order_code' => $data['info']['order_code']));

        //甲方-委托方（客户名称）
        $data['info']['customer_name'] = $this->Mhouses_customers->get_one('name', array('id' => $order['customer_id']))['name'];
            
        //投放起止时间
        $data['info']['release_start_time'] = $order['release_start_time'];
        $data['info']['release_end_time'] = $order['release_end_time'];

        //上画完成时间
        $data['complete_date'] = date('Y年m月d日', strtotime($data['info']['draw_finish_time']));

        /********验收图片**********/
        $data['inspect_images'] = $this->Mhouses_order_inspect_images->get_inspect_img(array('A.order_id' => $id, 'A.type' => 2));

        //获取点位列表
        $where['in'] = array('B.id' => explode(',', $data['info']['point_ids']));
        $data['points'] = $this->Mhouses_points->get_confirm_points($where, array('A.id' => 'asc', 'B.id' => 'asc'), array('media_code', 'C.size'));

        $data['info']['order_type'] = $order['order_type'];
        if ($data['info']['order_type'] == '1') {    //灯箱
            //统计大灯箱、中灯箱、小灯箱套数
            $make = $this->get_make_info($data['info']);
            $make_info = multi_arr_sort($make['make_num'], 'spec_id');
            $data['number'] = array();
            foreach($make_info as $k=>$v){
                if(!isset($data['number'][$v['spec_name']])){
                    $data['number'][$v['spec_name']] = $v['counts'];
                }else{
                    $data['number'][$v['spec_name']] += $v['counts'];
                }
            }

            //广告总套数
            $data['total_num'] = $make['total_counts'];
            $data['volume'] = array_column($data['points'], 'counts' ,'media_id');

            $this->load->view('housesorders/confirmation/light', $data);
        } elseif ($data['info']['order_type'] == '2') {  //户外高杆
            //高杆数
            $data['total_num'] = $this->get_make_info($data['info'])['high_count'];

            $this->load->view('orders/confirmation/high', $data);
        } elseif ($data['info']['order_type'] == '3' || $data['info']['order_type'] == '4') {   //led
            $this->load->view('housesorders/confirmation/led', $data);
        }
    }


    /*
     * 订单详情页面
     */
    public function detail($id){
        $data = $this->data;
        $data['info'] = $this->Mhouses_changepicorders->get_one('*',array('id' => $id));

        $order = $this->Mhouses_orders->get_one('*', array('order_code' => $data['info']['order_code']));

        //订单总价
        $data['info']['total_price'] = $order['total_price'];

        //客户名称
        $data['info']['customer_name'] = $this->Mhouses_customers->get_one('name', array('id' => $order['customer_id']))['name'];


        //业务员
        $data['info']['salesman'] = $this->Msalesman->get_one('name, phone_number', array('id' => $order['sales_id']));

        //投放时间
        $data['info']['release_start_time'] = $order['release_start_time'];
        $data['info']['release_end_time'] = $order['release_end_time'];

        //换画点位
        $data['info']['selected_points'] = $this->Mhouses_points->get_points_lists(array('in' => array('A.id' => explode(',', $data['info']['point_ids']))));

        //广告画面
        $data['info']['adv_img'] = $data['info']['adv_img'] ? explode(',', $data['info']['adv_img']) : array();

        //验收图片
        $data['info']['inspect_img'] = $this->Mhouses_order_inspect_images->get_inspect_img(array('A.order_id' => $id, 'A.assign_type' => 3));
        
        //换画派单列表
        $data['info']['assign_down_list'] = $this->Mhouses_assign_down->get_join_lists(['A.order_id' => $id, 'A.is_del' => 0]);
        
        //每个媒体对应套数
//         $where_point['in'] = array('B.id' => explode(',', $data['info']['point_ids']));
//         $points = $this->Mhouses_points->get_confirm_points($where_point, array('A.id' => 'asc', 'B.id' => 'asc'), array('media_code', 'C.size'));
//         $data['number'] = array_column($points, 'counts', 'media_id');

        $data['info']['order_type'] = $order['order_type'];
            
        //制作公司
        $data['info']['make_company'] = $this->Mmake_company->get_one('company_name', array('id' => $data['info']['make_company_id']))['company_name'];
        $data['status_text'] = C('housesorder.houses_order_status.text');
       

        //获取对应订单状态的操作信息
        $operate_time = $this->Mstatus_operate_time->get_lists("value,operate_remark,operate_time",array("order_id" => $id , 'type' => 2));
        if($operate_time){
            $data['time'] = array_column($operate_time,"operate_time","value");
            $data['operate_remark'] = array_column($operate_time,"operate_remark","value");
        }


        $data['id'] = $id;

        $this->load->view('housesorders/change_pic/detail', $data);
    }

    /*
     * 更新换画订单状态
     * @auth:jianming
     */
    public  function ajax_update_status(){
        if ($this->data['pur_code'] == 1) {
            $this->return_failed('您没有更新订单状态的权限！');
        }
        
        if($this->input->is_ajax_request()){
            $id = $this->input->post("id");
            $status = $this->input->post("status");
            $operate_remark = $this->input->post("remark");
            $order_code = $this->input->post("order_code");
            $count = $this->Mstatus_operate_time->count(array("order_id"=>$id,"value"=>$status, 'type' => 2));
            if($count){
                $res = $this->Mstatus_operate_time->update_info(
                    array('operate_remark' => $operate_remark,"operate_time" => date("Y-m-d H:i:s")),
                    array('order_id' => $id,"value" => $status, 'type' => 2)
                );
                //删除该状态以下的所有状态
                $where['order_id'] = $id;
                $where['value>'] = $status;
                $where['type'] = 2;
                $res = $this->Mstatus_operate_time->delete($where);
            }else{
                $post_data['order_id'] = $id;
                $post_data['value'] = $status;
                $post_data['operate_time'] = date("Y-m-d H:i:s");
                $post_data['operate_remark'] = $operate_remark;
                $post_data['type'] = 2;
                $this->Mstatus_operate_time->create($post_data);
            }
            $data = $this->data;
            $status_text = C('order.order_status.text');

            $this->write_log($data['userInfo']['id'],2,"  更新订单:".$order_code."状态：".$status_text[$status]);
            //同时更新对应的订单
            $result = $this->Mhouses_changepicorders->update_info(array("order_status"=>$status),array("id"=>$id));
            $this->return_success();
        }
    }

    private function get_make_info($data) {
        //制作数量
        $make_num = $this->Mhouses_points->get_make_info(array('in' => array('A.id' => explode(',', $data['point_ids'])), 'C.order_id' => $data['id'], 'C.type' => 2));

        //计算总套数和总张数
        $high_count = 0;
        $total_counts = 0;
        $total_num = 0;
        foreach ($make_num as $value) {
            $total_counts += $value['counts'];
            $high_count += $value['high_count'];
            $total_num += $value['make_num'];
        }

        return array('make_num' => $make_num, 'total_counts' => $total_counts, 'high_count' => $high_count, 'total_num' => $total_num);
    }


    /*
     * 上传广告画面
     * @auth:jianming
     */
    public function  upload_adv_img($order_id){
        $data = $this->data;
        if(IS_POST){
            $cover_img = $this->input->post("cover_img");
            $adv_img = implode(",",$cover_img);
            $res = $this->Mhouses_changepicorders->update_info(array("adv_img"=>$adv_img), array("id"=>$order_id));
            if ($res) {
                $this->success("保存广告画面成功！", "/houseschangepicorders/detail/".$order_id);
            } else {
                $this->error("操作失败！请重试！");
            }
        } else {
            //获取广告画面的图片
            $info = $this->Mhouses_changepicorders->get_one("adv_img",array("id"=>$order_id));
            $data['adv_img'] = "";
            $data['order_id'] = $order_id;
            if($info['adv_img']){
                $data['adv_img'] = explode(",",$info['adv_img']);
            }
            $this->load->view('housesorders/upload_adv_img', $data);
        }

    }


    /**
     * 导出换画点位列表
     */
	public function export($id, $type) {
        //加载phpexcel
        $this->load->library("PHPExcel");

        //设置表头
        $table_header =  array(
	        '点位编号'=>"code",
	       	'楼盘'=>"houses_name",
	        '组团'=>"houses_area_name",
        	'楼栋'=>"ban",
        	'单元'=>"unit",
        	'楼层'=>"floor",
	        '点位位置'=>"addr",
	        '规格'=>"size",
       	);

        $i = 0;
        foreach($table_header as  $k=>$v){
            $cell = PHPExcel_Cell::stringFromColumnIndex($i).'1';
            $this->phpexcel->setActiveSheetIndex(0)->setCellValue($cell, $k);
            $i++;
        }
		
        
        
        
        $order = $this->Mhouses_changepicorders->get_one('*', array('id' => $id));
        
        //查找订单中的客户id
        $r_order = $this->Mhouses_orders->get_one('*', array('order_code' => $order['order_code']));

        $where['in']['A.id'] = explode(',', $order['point_ids']);

        $customers = array_column($this->Mhouses_customers->get_lists("id,name", array('is_del' => 0)), 'name', 'id'); //客户列表

        $list = $this->Mhouses_points->get_points_lists($where);

        $h = 2;
        foreach($list as $key=>$val){
            $j = 0;
            foreach($table_header as $k => $v){
                $cell = PHPExcel_Cell::stringFromColumnIndex($j++).$h;
				
            	$value = '';
                if($v == 'addr') {
                	if(isset($data['point_addr'][$val[$v]]))
                		$value = $data['point_addr'][$val[$v]];
                }else {
                	$value = $val[$v];
                }
                $this->phpexcel->getActiveSheet(0)->setCellValue($cell, $value);
            }
            $h++;
        }

        $this->phpexcel->setActiveSheetIndex(0);
        // 输出
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename=换画点位表（客户：'.$customers[$r_order['customer_id']].'）.xls');
        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel5');
        $objWriter->save('php://output');
    }


}

