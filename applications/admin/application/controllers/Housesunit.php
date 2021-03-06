<?php
/**
 * 楼盘区域管理控制器
 * 867332352@qq.com
 */
defined('BASEPATH') or exit('No direct script access allowed');
class Housesunit extends MY_Controller{

    public function __construct(){
        parent::__construct();
        $this->load->model([
        	'Model_houses' => 'Mhouses',
        	'Model_houses_group' => 'Mhouses_group',
            'Model_houses_area' => 'Mhouses_area',
        	'Model_houses_unit' => 'Mhouses_unit'
         ]);
        $this->data['code'] = 'community_manage';
        $this->data['active'] = 'houses_unit_lists';
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

        if ($this->input->get('name')) $where['like']['name'] = $this->input->get('name');
        if ($this->input->get('houses_id') != 'all' && !empty($this->input->get('houses_id'))) {
        	$where['houses_id'] = $this->input->get('houses_id') ? $this->input->get('houses_id') : 1;
        }
        

        $data['name'] = $this->input->get('name');
        $data['houses_id'] = $this->input->get('houses_id');

        $data['list'] = $this->Mhouses_unit->get_lists('*',$where,[],$size,($page-1)*$size);
        $data_count = $this->Mhouses_unit->count($where);
        $data['page'] = $page;
        $data['data_count'] = $data_count;

        //获取分页
        $pageconfig['base_url'] = "/housesunit";
        $pageconfig['total_rows'] = $data_count;
        $this->pagination->initialize($pageconfig);
        $data['pagestr'] = $this->pagination->create_links(); // 分页信息
        
        
        if(count($data['list']) > 0) {
        	$unit_ids = array_column($data['list'], 'id');
        	$where1['in']['A.id'] = $unit_ids;
        	$join_arr = $this->Mhouses_unit->get_join_info($where1);
        
        	$data['houses_name'] = array_column($join_arr, 'houses_name', 'id');
        	$data['group_name'] = array_column($join_arr, 'group_name', 'id');
        	$data['area_name'] = array_column($join_arr, 'area_name', 'id');
        }
        
		
        $data['houses_type'] = C("public.houses_type");
        
        $data['list1'] = $this->Mhouses->get_lists('id,name', ['is_del' => 0]);
        
        $this->load->view("housesunit/index",$data);
    }

    /*
     * 添加
     * 867332352@qq.com
     */
    public function  add(){
        $data = $this->data;
        $data['title'] = array("单元管理","新增单元");

        if(IS_POST){
            $post = $this->input->post();
            $post['creator'] = $data['userInfo']['id'];
            $post['create_time'] = date("Y-m-d H:i:s");

            $result = $this->Mhouses_unit->create($post);
            if($result){
                $this->write_log($data['userInfo']['id'],1,"新增单元：".$post['name']);
                $this->success("添加成功","/housesunit");
            }else{
                $this->error("添加失败");
            }

        }
        
        $where['is_del'] = 0;
        $data['list'] = $this->Mhouses->get_lists('id,name',$where);

        $this->load->view("housesunit/add",$data);
    }

    /*
     * 编辑
     * 867332352@qq.com
     */
    public function edit($id = 0){
        $data = $this->data;
        $data['title'] = array("单元管理","编辑单元");

        if(IS_POST){
            $post = $this->input->post();
            //$post['update_user'] = $data['userInfo']['id'];
            //$post['update_time'] = date("Y-m-d H:i:s");
            $result = $this->Mhouses_unit->update_info($post,array("id"=>$id));
            if($result){
                $this->write_log($data['userInfo']['id'],2,"编辑单元：".$post['name']);
                $this->success("编辑成功","/housesunit");
            }else{
                $this->error("编辑失败");
            }

        }

        $info = $this->Mhouses_unit->get_one("*",array("id"=>$id));
        if(empty($info) || !isset($info)){
            die("非法参数");
        }
        $data['info'] = $info;
		
        if(isset($info['houses_id'])) {
        	$data['group_arr'] = $this->Mhouses_group->get_lists('id,group_name',['houses_id'=>$info['houses_id']]);
        	$data['area_arr'] = $this->Mhouses_area->get_lists('id,name',['houses_id'=>$info['houses_id']]);
        }
        
        var_dump($data['group_arr']);
        
        $where['is_del'] = 0;
        $data['list'] = $this->Mhouses->get_lists('id,name',$where);


        $this->load->view("housesunit/edit",$data);
    }

    /*
    * 列表
    * 867332352@qq.com
    */
    public function del($id = 0){
        $data = $this->data;
        $name = $this->Mhouses_area->get_one("name",array("is_del"=>0,"id"=>$id));
        if(!$name){
            die("非法参数");
        }
        $where['id'] = $id;
        $list['is_del'] = 1;
        $del = $this->Mhouses_unit->update_info($list, $where);
        if($del){
            $this->write_log($data['userInfo']['id'],3," 删除楼盘区域：".$name['name']);
            $this->success("删除成功！","/housesunit");

        }else{
            $this->error("删除失败！");
        }
    }
    
    /*
     * ajax获取组团、楼栋等信息
     */
    public function ajax_get_info() {
    	$houses_id = $this->input->post('houses_id');
    	$group_id = $this->input->post('group_id');
    	
    	if($houses_id) {
    		$where['houses_id'] = $houses_id;
    		$group_arr = $this->Mhouses_group->get_lists('id,group_name', $where);
    		
    		if($group_id) $where['group_id'] = $group_id;
    		$area_arr = $this->Mhouses_area->get_lists('id,name', $where);
    	}
    	
    	
    	$this->return_json(['group_arr' => $group_arr, 'area_arr' => $area_arr]);
    	
    }


}