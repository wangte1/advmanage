<?php
/**
 * 点位管理控制器
 * 867332352@qq.com
 */
defined('BASEPATH') or exit('No direct script access allowed');
class Housespoints extends MY_Controller{

    public function __construct(){
        parent::__construct();
        $this->load->model([
            'Model_houses_points' => 'Mhouses_points',
        	'Model_houses' => 'Mhouses',
        	'Model_houses_area' => 'Mhouses_area',
        	'Model_area' => 'Marea',
        	'Model_houses_points_format' => 'Mhouses_points_format',
        	'Model_houses_customers' => 'Mhouses_customers',
         ]);
        $this->data['code'] = 'community_manage';
        $this->data['active'] = 'houses_points_list';
        
        $this->data['order_type_text'] = C('order.houses_order_type'); //点位类型
    }

    /*
    * 列表
    * 867332352@qq.com
    */
    public function index(){
        $data = $this->data;
        $pageconfig = C('page.page_lists');
        $this->load->library('pagination');

        $page =  intval($this->input->get("per_page",true)) ?  : 1;
        $size = $pageconfig['per_page'];
        $where['is_del'] = 0;
        
        $is_lock = $this->input->get('is_lock');
        if(strlen($is_lock)){
            $where['is_lock'] = (int) $this->input->get('is_lock');
            $data['is_lock'] = $this->input->get('is_lock');
        }
        
        if ($this->input->get('type_id')) $where['type_id'] = $this->input->get('type_id');
        if ($this->input->get('houses_id')) $where['houses_id'] = $this->input->get('houses_id');
        if ($this->input->get('area_id')) $where['area_id'] = $this->input->get('area_id');
        if ($this->input->get('point_status')) $where['point_status'] = $this->input->get('point_status');
        if ($this->input->get('customer_id')) $where['customer_id'] = $this->input->get('customer_id');
        
        $data['point_status'] = $this->input->get('point_status');
        $data['area_id'] = $this->input->get('area_id');
        $data['type_id'] = $this->input->get('type_id');
        $data['customer_id'] = $this->input->get('customer_id');
        $data['houses_id'] = $this->input->get('houses_id');
        if($data['houses_id']) $data['area_list'] = $this->get_area_info($data['houses_id']);
        
        $data['list'] = $this->Mhouses_points->get_lists('*',$where,[],$size,($page-1)*$size);
        $data_count = $this->Mhouses_points->count($where);
        $data['page'] = $page;
        $data['data_count'] = $data_count;
        
        $data['hlist'] = $this->Mhouses->get_lists('id,name',['is_del'=>0]);
        $data['alist'] = $this->Mhouses_area->get_lists('id,name',['is_del'=>0]);
        $data['tlist'] = $this->Mhouses_points_format->get_lists('id,type',['is_del'=>0]);
        
        //获取分页
        $pageconfig['base_url'] = "/housespoints";
        $pageconfig['total_rows'] = $data_count;
        $this->pagination->initialize($pageconfig);
        $data['pagestr'] = $this->pagination->create_links(); // 分页信息
        
        $data['customers'] = $this->Mhouses_customers->get_lists("id,name", array('is_del' => 0)); //客户列表
        $data['customer_name'] = array_column($data['customers'], 'name', 'id');
        $data['houses_type'] = C("public.houses_type");

        $this->load->view("housespoints/index",$data);
    }

    /*
     * 添加
     * 867332352@qq.com
     */
    public function  add(){
        $data = $this->data;
        $data['title'] = array("楼盘管理","添加楼盘");

        if(IS_POST){
            $post = $this->input->post();
            $post['creator'] = $data['userInfo']['id'];
            $post['create_time'] = date("Y-m-d H:i:s");

            $result = $this->Mhouses_points->create($post);
            if($result){
                $this->write_log($data['userInfo']['id'],1,"新增点位：".$post['code']);
                $this->success("添加成功","/housespoints");
            }else{
                $this->error("添加失败");
            }

        }

        $data['houses_type'] = C("public.houses_type");
        
       	//$data['hlist'] = $this->get_houses_info("贵州省", "贵阳市", "南明区");
        $data['hlist'] = $this->Mhouses->get_lists('id,name',['is_del'=>0]);
       	
       	$data['tlist'] = $this->Mhouses_points_format->get_lists('id,type',['is_del'=>0]);
       	
        $this->load->view("housespoints/add",$data);
    }

    /*
     * 编辑
     * 867332352@qq.com
     */
    public function edit($id = 0){
        $data = $this->data;
        $data['title'] = array("站台管理","添加站台");

        if(IS_POST){
            $post = $this->input->post();
            if(isset($post['cover_img'])){
            	$post['images'] = implode(';', $post['cover_img']);
            	unset($post['cover_img']);
            }
            //$post['update_user'] = $data['userInfo']['id'];
            //$post['update_time'] = date("Y-m-d H:i:s");
            $result = $this->Mhouses_points->update_info($post,array("id"=>$id));
            if($result){
                $this->write_log($data['userInfo']['id'],2,"编辑点位：".$post['code']);
                $this->success("编辑成功","/housespoints");
            }else{
                $this->error("编辑失败");
            }

        }

        $info = $this->Mhouses_points->get_one("*",array("id"=>$id));
        if(empty($info) || !isset($info)){
            die("非法参数");
        }
        $data['info'] = $info;
        
        $tmplist = $this->Mhouses->get_lists('id,name,province,city,area', ['id' => $info['houses_id']]);
        
        if(count($tmplist) > 0 ) {
        	//var_dump($tmplist[0]);
        	$data['d_houses'] = $tmplist[0];
        	$data['hlist'] = $this->get_houses_info($tmplist[0]['province'], $tmplist[0]['city'], $tmplist[0]['area']);
        	$data['alist'] = $this->get_area_info($tmplist[0]['id']);
        }
        
        $data['tlist'] = $this->Mhouses_points_format->get_lists('id,type', ['is_del'=>0]);
        
        if ($info['houses_id']) $where['houses_id'] = $info['houses_id'];
        if ($info['area_id']) $where['area_id'] = $info['area_id'];
         
        $data['buf'] = $this->Mhouses_points->get_lists('ban,unit,floor',$where,$order_by = array(), $pagesize = 0,$offset = 0,  $group_by = array('ban','unit','floor'));

        $this->load->view("housespoints/edit",$data);
    }

    /*
    * 列表
    * 867332352@qq.com
    */
    public function del($id = 0){
        $data = $this->data;
        $name = $this->Mhouses->get_one("name",array("is_del"=>0,"id"=>$id));
        if(!$name){
            die("非法参数");
        }
        $where['id'] = $id;
        $list['is_del'] = 1;
        $del = $this->Mhouses->update_info($list, $where);
        if($del){
            $this->write_log($data['userInfo']['id'],3," 删除楼盘：".$name['name']);
            $this->success("删除成功！","/houses");

        }else{
            $this->error("删除失败！");
        }
    }
    
    /*
     * ajax获取楼盘信息
     */
    public function ajax_houses_info() {
    	if ($this->input->post('province')) $where['province'] = $this->input->post('province');
    	if ($this->input->post('city')) $where['city'] = $this->input->post('city');
    	if ($this->input->post('area')) $where['area'] = $this->input->post('area');
    	$list = $this->get_houses_info($where['province'], $where['city'], $where['area']);
    	$this->return_json($list);
    }
    
    /*
     * ajax获取楼盘区域信息
     */
    public function ajax_area_info() {
    	if ($this->input->post('houses_id')) $where['houses_id'] = $this->input->post('houses_id');
    	$list = $this->get_area_info($where['houses_id']);
    	$this->return_json($list);
    }
    
    /*
     * ajax获取楼栋、单元、楼层信息
     */
    public function get_buf_info() {
    	if ($this->input->post('houses_id')) $where['houses_id'] = $this->input->post('houses_id');
    	if ($this->input->post('area_id')) $where['area_id'] = $this->input->post('area_id');
    	
    	$list = $this->Mhouses_points->get_lists('ban,unit,floor',$where,$order_by = array(), $pagesize = 0,$offset = 0,  $group_by = array('ban','unit','floor'));
    	
    	$this->return_json(['code' => 1, 'list' => $list]);
    }
    
    /*
     * 获取楼盘信息
     */
    public function get_houses_info($province = '', $city = '', $area = '') {
    	$where['is_del'] = 0;
    	if ($province) $where['province'] = $province;
    	if ($city) $where['city'] = $city;
    	if ($area) $where['area'] = $area;
    	$list = $this->Mhouses->get_lists('id,name,province,city,area',$where);
    	return $list;
    }
    
    /*
     * 获取楼盘区域信息
     */
    public function get_area_info($houses_id = 0) {
    	
    	$where['is_del'] = 0;
    	if ($houses_id) $where['houses_id'] = $houses_id;
    	$list = $this->Mhouses_area->get_lists('id,name',$where);
    	return $list;
    	
    }
    
    /*
     * 获取楼盘区域信息
     * @author yonghua 
     */
    public function get_area() {
        
        $where['is_del'] = 0;
        $houses_id = (int) $this->input->post('houses_id');
        if ($houses_id) $where['houses_id'] = $houses_id;
        $list = $this->Mhouses_area->get_lists('id,name',$where);
        if(!$list) $this->return_json(['code' => 0]);
        $this->return_json(['code' => 1, 'list' => $list]);
        
    }

}