<?php
/**
 * 点位管理控制器
 * 1034487709@qq.com
 */
defined('BASEPATH') or exit('No direct script access allowed');
class Points extends MY_Controller{

    public function __construct(){
        parent::__construct();
        $this->load->model([
            'Model_points' => 'Mpoints',
            'Model_medias' => 'Mmedias',
            'Model_specifications' => 'Mspecifications',
            'Model_area' => 'Marea',
            'Model_customers' => 'Mcustomers',
            'Model_customer_project' => 'Mcustomer_project',
        ]);
        $this->pageconfig = C('page.page_lists');
        $this->load->library('pagination');
        $this->data['code'] = 'resources_manage';
        $this->data['active'] = 'points_list';
    }

    /*
    * 列表
    * 1034487709@qq.com
    */
    public function index(){
        $data = $this->data;
        $pageconfig = C('page.page_lists');
        $this->load->library('pagination');
        $page = $this->input->get_post('per_page') ? : '1';

        $where = $where_query = array();
        if ($this->input->get('media_type') != 'all') {
            $where['B.type'] = $where_query['type'] = $this->input->get('media_type') ? $this->input->get('media_type') : 1;
        }

        if ($this->input->get('point_status')) $where['A.point_status'] = $this->input->get('point_status');
        if ($this->input->get('media_id')) $where['B.id'] = $this->input->get('media_id');
        if ($this->input->get('customer_id')) $where['D.id'] = $this->input->get('customer_id');
        if ($this->input->get('spec_id') && count(array_filter($this->input->get('spec_id'))) > 0) $where['in']['C.id'] = $this->input->get('spec_id');
        if ($this->input->get('is_lock') != '') $where['A.is_lock'] = $this->input->get('is_lock');
        if ($this->input->get('lock_customer_id')) $where['A.lock_customer_id'] = $this->input->get('lock_customer_id');
        if ($this->input->get('address')) $where['like']['A.address'] = $this->input->get('address');

        //即将到期
        $data['expire_time'] = $this->input->get("expire_time");
        if($this->input->get("expire_time")) {
            $where['E.release_end_time>='] = date("Y-m-d");
            $where['E.release_end_time<='] =  date("Y-m-d",strtotime("+7 day"));
            $where['E.order_status'] =  C('order.order_status.code.in_put');
        }

        $data['media_type'] = $this->input->get('media_type');
        $data['media_id'] = $this->input->get('media_id');
        $data['customer_id'] = $this->input->get('customer_id');
        $data['spec_id'] = $this->input->get('spec_id') ? array_filter($this->input->get('spec_id')) : array();
        $data['point_status'] = $this->input->get('point_status');
        $data['is_lock'] = $this->input->get('is_lock');
        $data['lock_customer_id'] = $this->input->get('lock_customer_id');
        $data['address'] = $this->input->get('address');

        $data['list'] = $this->Mpoints->lists($where, ($page-1)*$pageconfig['per_page'], $pageconfig['per_page']);
        $data_count = $this->Mpoints->get_point_count($where);
        $data['data_count'] = $data_count;
        $data['page'] = $page;

        //获取分页
        $pageconfig['base_url'] = "/points";
        $pageconfig['total_rows'] = $data_count;
        $this->pagination->initialize($pageconfig);
        $data['pagestr'] = $this->pagination->create_links(); // 分页信息

        $data['medias'] = $this->Mmedias->get_lists("id, code, name, type", array_merge($where_query, array('is_del' => 0)), array('sort' => 'asc')); //媒体列表
        $data['customers'] = $this->Mcustomers->get_lists("id,customer_name", array('is_del' => 0)); //客户列表
        $data['customer_name'] = array_column($data['customers'], 'customer_name', 'id');
        $data['specifications'] = $this->Mspecifications->get_lists("id,name,size", array_merge($where_query, array('is_del' => 0))); //规格列表

        $data['project'] = array_column($this->Mcustomer_project->get_lists('id, project_name', array('is_del' => 0)), 'project_name', 'id');

        $this->load->view("points/index",$data);
    }

    /*
     * 添加
     * 1034487709@qq.com
     */
    public function  add(){
        $data = $this->data;
        $data['title'] = array("点位管理","新建点位");

        if(IS_POST){
            $post = $this->input->post();
            if(isset($post['cover_img'])){
                $post['images'] = implode(';', $post['cover_img']);
                unset($post['cover_img']);
            }
            $post['address'] = $post['province'].",".$post['city'].','.$post['area'].",".$post['street_address'];
            unset($post['media_type'], $post['province'], $post['city'], $post['area'], $post['street_address']);

            $post['create_user'] = $data['userInfo']['id'];
            $post['create_time'] = date("Y-m-d H:i:s");

            $post['update_user'] = $data['userInfo']['id'];
            $post['update_time'] = date("Y-m-d H:i:s");
            $result = $this->Mpoints->create($post);
            if($result){
                $this->write_log($data['userInfo']['id'],1,"新增点位");
                $this->success("添加成功","/points");
            }else{
                $this->error("添加失败");
            }

        }

        //获取所有站台
        $data['medias'] = $this->Mmedias->get_lists("id,name,code",array("is_del"=>0), array('sort' => 'asc'));

        $data['specifications'] = $this->Mspecifications->get_lists("id,name,size",array("is_del"=>0));

        //获取省级
        $data['province'] = $this->Marea->get_lists("id,area_name,parent_id,level",array("parent_id"=>0));
        //城市
        $data['city'] = $this->Marea->get_lists("id,area_name,parent_id,level",array("parent_id"=>35560));
        //地区
        $data['area'] = $this->Marea->get_lists("id,area_name,parent_id,level",array("parent_id"=>35561));


        $data['customer_type'] = C("public.customer_type");
        $this->load->view("points/add",$data);
    }

    /*
     * 编辑
     * 1034487709@qq.com
     */
    public function edit($id = 0){
        $data = $this->data;
        $data['title'] = array("客户管理","编辑客户");
        $per_page = $_GET['per_page'];
        $data['per_page'] = $per_page;


        if(IS_POST){
            $post = $this->input->post();
            if(isset($post['cover_img'])){
                $post['images'] = implode(';', $post['cover_img']);
                unset($post['cover_img']);
            }
            $post['address'] = $post['province'].",".$post['city'].','.$post['area'].",".$post['street_address'];
            unset($post['province']);
            unset($post['city']);
            unset($post['area']);
            unset($post['street_address']);

            $post['update_user'] = $data['userInfo']['id'];
            $post['update_time'] = date("Y-m-d H:i:s");
            $result = $this->Mpoints->update_info($post,array("id"=>$id));

            if($result){
                $this->write_log($data['userInfo']['id'],2,"编辑点位：");
                $this->success("编辑成功","/points?per_page=".$per_page);
            }else{
                $this->error("编辑失败");
            }

        }

        $info = $this->Mpoints->get_one("*",array("id"=>$id,"is_del"=>0));
        if(empty($info) || !isset($info)){
            die("非法参数");
        }
        $data['info'] = $info;

        //该点位所属媒体
        $media = $this->Mmedias->get_one("id, name, code, type",array('id' => $info['media_id'], "is_del" => 0));
        $data['info']['media_type'] = $media['type'];
        $data['info']['media_name'] = $media['name'];

        $data['specifications'] = $this->Mspecifications->get_lists("id,name,size",array("type" => $media['type'],"is_del" => 0));

        //获取省级
        $data['province'] = $this->Marea->get_lists("id,area_name,parent_id,level",array("parent_id"=>0));
        $data['address'] = explode(",",$info['address']);

        $data['province_id'] = $this->Marea->get_one('id', array('area_name' => $data['address'][0], 'parent_id' => 0))['id'];
        $data['city_id'] = $this->Marea->get_one('id', array('area_name' => $data['address'][1], 'parent_id' => $data['province_id']))['id'];
        $data['area_id'] = $this->Marea->get_one('id', array('area_name' => $data['address'][2], 'parent_id' => $data['city_id']))['id'];

        $this->load->view("points/edit",$data);
    }

    /*
    * 删除点位
    * 1034487709@qq.com
    */
    public function del($id = 0){
        $data = $this->data;
        $data['title'] = array("点位管理","删除点位");
        $name = $this->Mpoints->get_one("points_code",array("is_del"=>0,"id"=>$id));
        if(!$name){
            die("非法参数");
        }
        $where['id'] = $id;
        $list['is_del'] = 1;
        $del = $this->Mpoints->update_info($list, $where);
        if($del){
            $this->write_log($data['userInfo']['id'],3," 删除点位编号：".$name['points_code']);
            $this->success("删除成功!!","/points");

        }else{
            $this->error("删除失败!!");
        }
    }


    /*
     * 获取经纬度
     */
    public function get_points(){
        if($this->input->is_ajax_request()){
            $address_info = $this->input->post("address_info");
            $address_info_arr = explode("-",$address_info);
            $province = $address_info_arr[0];
            $city = $address_info_arr[1];
            $area = $address_info_arr[2];
            $address = $this->input->post("address");
            $addr_str = $province.$city.$area.$address;
            $json = file_get_contents("http://api.map.baidu.com/geocoder/v2/?output=json&ak=".C("public.baidu_key")."&address=".$addr_str."");
            $location_info = json_decode($json);
            if ($location_info->status == 0) {
                $points=array(
                    'lng'=>$location_info->result->location->lng,
                    'lat'=>$location_info->result->location->lat,
                    'errorno'=>'0'
                );
            }
            $this->return_json($points);

        }
    }


    public function add_specifications(){
        $data = $this->data;
        if($this->input->is_ajax_request()){
            $post_data = $this->input->post();
            $post_data['create_user'] = $post_data['update_user'] = $data['userInfo']['id'];
            $post_data['create_time'] = $post_data['update_time'] = date('Y-m-d H:i:s');
            $id = $this->Mspecifications->create($post_data);
            if($id){
                $this->return_success(array("id"=>$id));
            }
            else{
                $this->return_failed();
            }
        }
    }


    /*
     * 根据媒体类型获取媒体名称和规格
     */
    public function get_media_spec(){
        $media_type = $this->input->post('media_type');
        $media_list = $this->Mmedias->get_lists('id, code, name', array('type' => $media_type, 'is_del' => 0), array('sort' => 'asc'));
        $msg = $this->input->post('search') ? '全部' : '---请选择---';
        $media_option = '<option value="">'.$msg.'</option>';
        if ($media_list) {
            foreach ($media_list as $key => $value) {
                $media_option .= '<option value="'.$value['id'].'">'.$value['name'].'('.$value['code'].')'.'</option>';
            }
        }

        $spec_list = $this->Mspecifications->get_lists('id, name, size', array('type' => $media_type, 'is_del' => 0));
        $spec_option = '<option value="">'.$msg.'</option>';
        if ($spec_list) {
            foreach ($spec_list as $key => $value) {
                $spec_option .= '<option value="'.$value['id'].'">'.$value['name'].'('.$value['size'].')'.'</option>';
            }
        }
        $this->return_json(array('flag' => true, 'media_option' => $media_option, 'spec_option' => $spec_option));
    }


    /*
      * 判断点位编号的唯一性
      * 1034487709@qq.com
     */
    public function  get_unique_pointscode(){
        if($this->input->is_ajax_request()){
            $media_id = trim($this->input->post("media_id"));
            $points_code = trim($this->input->post("points_code"));
            $count = $this->Mpoints->count(array('media_id' => $media_id, 'points_code' => $points_code));
            if($count){
                $this->return_success(array("count"=>$count));
            }else{
                $this->return_failed();
            }
        }
    }

    /*
      * 联动菜单
      * 1034487709@qq.com
    */
    public function  get_area(){
        if($this->input->is_ajax_request()){
            $id = intval($this->input->post("id"));
            $area = $this->Marea->get_lists("id,area_name,parent_id,level",array("parent_id"=>$id));
            if($area){
                $this->return_success($area);
            }else{
                $this->return_failed();
            }
        }
    }



    /*
      * 锁定点位
      * 1034487709@qq.com
    */
    public  function lock_point(){
        $data = $this->data;
        if($this->input->is_ajax_request()){

            $lock_end_time = $this->input->post("lock_end_time");

            $expire_time = strtotime($lock_end_time." 23:59:59");

            $where['id'] =  intval($this->input->post("id"));
            $list['lock_start_time'] = $this->input->post("lock_start_time");
            $list['lock_end_time'] = $lock_end_time;
            $list['customer_id'] = intval($this->input->post("customer_id"));
            $list['expire_time'] = $expire_time-86400;
            $list['point_status'] = 2;

            $result = $this->Mpoints->update_info($list, $where);

            if($result){
                $info  = $this->Mpoints->get_one("points_code",array("id"=>intval($this->input->post("id"))));
                $this->write_log($data['userInfo']['id'],2," 锁定点位 点位编号为".$info['points_code']);
                $this->return_success();
            }else{
                $this->return_failed();
            }
        }
    }

    /*
     * 批量锁定点位
     *  1034487709@qq.com
     */
    public function mutile_lock_point(){
        $data = $this->data;
        if($this->input->is_ajax_request()){

            $ids = $this->input->post("ids");
            $chk_code = $this->input->post("chk_code");
            $lock_end_time = $this->input->post("lock_end_time");
            $expire_time = strtotime($lock_end_time." 23:59:59");
            $list['lock_start_time'] = $this->input->post("lock_start_time");
            $list['lock_end_time'] = $lock_end_time;
            $list['customer_id'] = intval($this->input->post("customer_id"));
            $list['expire_time'] = $expire_time-86400;
            $list['point_status'] = 2;

            foreach($ids as $val){
               $result = $this->Mpoints->update_info($list, array("id"=>$val));
            }

            if($result){
                $str_code = "";
                foreach($chk_code as $val){
                    $str_code.="-".$val;
                }
                $this->write_log($data['userInfo']['id'],2," 批量锁定点位 点位编号为".$str_code);
                $this->return_success();
            }else{
                $this->return_failed();
            }

        }
    }


    /*
      * 批量点位解锁
      * 1034487709@qq.com
    */
    public  function mutile_unlock_point(){
        $data = $this->data;
        if($this->input->is_ajax_request()){
            $ids = $this->input->post("ids");
            $chk_code = $this->input->post("chk_code");
            $list['point_status'] = 1;
            $list['customer_id'] = "";
            $list['lock_start_time'] = "";
            $list['lock_end_time'] = "";
            $list['expire_time'] = "";

            $where['in'] = array("id"=>$ids);
             $result = $this->Mpoints->update_info($list, $where);
            if($result){
                $str_code = "";
                foreach($chk_code as $val){
                    $str_code.="-".$val;
                }
                $this->write_log($data['userInfo']['id'],2," 批量解锁点位 点位编号为".$str_code);
                $this->return_success();
            }else{
                $this->return_failed();
            }


        }
    }

    /*
        * 获取点位对应的订单
        * 1034487709@qq.com
    */
    public function  get_orders_count(){
      if($this->input->is_ajax_request()){
            $id = intval($this->input->post("id"));//点位编号
            $orderid_info = $this->Mpoints->get_one("order_id",array("id"=>$id,"is_del"=>0));
            if($orderid_info){
               if($orderid_info['order_id']){
                   $this->return_failed();
                }else{
                   $this->return_success();
                }
            }else{
                $this->return_success();
            }

        }
    }


    /*
      * 导出数据
      * 1034487709@qq.com
    */
    public function out_excel(){
        if ($this->input->get('media_type') == 1 || $this->input->get('media_type') == 2) {
            if ($this->input->get('media_type')) $where['B.type'] = $this->input->get('media_type');
            if ($this->input->get('point_status')) $where['A.point_status'] = $this->input->get('point_status');
            if ($this->input->get('media_id')) $where['B.id'] = $this->input->get('media_id');
            if ($this->input->get('customer_id')) $where['D.id'] = $this->input->get('customer_id');
            if ($this->input->get('spec_id') && count(array_filter($this->input->get('spec_id'))) > 0) $where['in']['C.id'] = $this->input->get('spec_id');
            if ($this->input->get('is_lock') != '') $where['A.is_lock'] = $this->input->get('is_lock');
            if ($this->input->get('lock_customer_id')) $where['A.lock_customer_id'] = $this->input->get('lock_customer_id');
            if ($this->input->get('address')) $where['like']['A.address'] = $this->input->get('address');

            //即将到期
            if($this->input->get("expire_time")) {
                $where['E.release_end_time>='] = date("Y-m-d");
                $where['E.release_end_time<='] =  date("Y-m-d",strtotime("+7 day"));
                $where['E.order_status'] =  C('order.order_status.code.in_put');
            }

            //加载phpexcel
            $this->load->library("PHPExcel");

            //设置表头
            if ($this->input->get('media_type') == 1) {
                $table_header =  array(
                    '站台名称'=>"media_name",
                    '站台编号'=>"media_code",
                    '点位编号'=>"points_code",
                    '客户'=>"customer_id",
                    '规格名称'=>"guige_name",
                    '规格大小'=>"guige_size",
                    '状态'=>"point_status",
                );
            } else {
                $table_header =  array(
                    '高杆名称'=>"media_name",
                    '高杆编号'=>"media_code",
                    '点位编号'=>"points_code",
                    '客户'=>"customer_id",
                    '规格大小'=>"guige_size",
                    '状态'=>"point_status",
                );
            }

            $i = 0;
            foreach($table_header as  $k=>$v){
                $cell = PHPExcel_Cell::stringFromColumnIndex($i).'1';
                $this->phpexcel->setActiveSheetIndex(0)->setCellValue($cell, $k);
                $i++;
            }

            $customers = array_column($this->Mcustomers->get_lists("id,customer_name", array('is_del' => 0)), 'customer_name', 'id'); //客户列表

            $list = $this->Mpoints->lists($where);

            $h = 2;
            foreach($list as $key=>$val){
                $j = 0;
                foreach($table_header as $k => $v){
                    $cell = PHPExcel_Cell::stringFromColumnIndex($j++).$h;

                    if($v == "media_name"){
                        $value = $val['media_name'];
                    }

                    if($v == "guige_name"){
                        $value = $val['spec_name'];
                    } elseif ($v == "media_code"){
                        $value = $val['media_code'];
                    } elseif ($v == "guige_size"){
                        $value = $val['size'];
                    } elseif ($v == "customer_id") {
                        $value = isset($customers[$val['customer_id']]) ? $customers[$val['customer_id']] : '';
                    }else {
                        $value = $val[$v];
                    }

                    if($v == "point_status"){
                        $value = C('public.points_status')[$val['point_status']];
                    }
                    $this->phpexcel->getActiveSheet(0)->setCellValue($cell, $value.' ');
                }
                $h++;
            }

            $this->phpexcel->setActiveSheetIndex(0);
            // 输出
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename=点位表.xls');
            header('Cache-Control: max-age=0');

            $objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel5');
            $objWriter->save('php://output');
        } 
    }



}