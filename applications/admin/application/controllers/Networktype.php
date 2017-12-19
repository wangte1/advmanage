<?php
/**
 * 排班设置控制器
 */
defined('BASEPATH') or exit('No direct script access allowed');
class Networktype extends MY_Controller{

    public function __construct(){
        parent::__construct();
        $this->load->model([
        	'Model_network_type' => 'Mnetwork_type',
        	'Model_network_mod' => 'Mnetwork_mod'
         ]);
        
        $this->data['code'] = 'net_manage';
        $this->data['active'] = 'network_type_list';
    }

    /*
    * 列表
    */
    public function index(){
        $data = $this->data;
        $pageconfig = C('page.page_lists');
        $this->load->library('pagination');

        $page =  intval($this->input->get("per_page",true)) ?  : 1;
        $size = $pageconfig['per_page'];
        $where['is_del'] = null;
		
        if ($this->input->get('name')) $where['like']['name'] = $this->input->get('name');
        $data['name'] = $this->input->get('name');
        
        if ($this->input->get('mod')) {
        	$where['mod'] = (int)$this->input->get('mod');
        }else {
        	$where['mod'] = 1;
        }
        $data['mod'] = $this->input->get('mod');
        
        $data['list'] = $this->Mnetwork_type->get_lists('*',$where,array("sort"=>"asc"),$size,($page-1)*$size);
        $data_count = $this->Mnetwork_type->count($where);
        $data['page'] = $page;
        $data['data_count'] = $data_count;
        
        $data['modInfo'] = $this->getNetMod();

        //获取分页
        $pageconfig['base_url'] = "/networktype";
        $pageconfig['total_rows'] = $data_count;
        $this->pagination->initialize($pageconfig);
        $data['pagestr'] = $this->pagination->create_links(); // 分页信息

        $this->load->view("networktype/index",$data);
    }

    /*
     * 添加
     */
    public function  add(){
        $data = $this->data;

        if(IS_POST){
            $post = $this->input->post();
            $result = $this->Mnetwork_type->create($post);
            if($result){
                $this->success("添加成功","/networktype");
            }else{
                $this->error("添加失败");
            }

        }
        
        $data['modInfo'] = $this->getNetMod();

        $this->load->view("networktype/add",$data);
    }

    /*
     * 编辑
     */
    public function edit($id = 0){
        $data = $this->data;

        if(IS_POST){
            $post = $this->input->post();
            $result = $this->Mnetwork_type->update_info($post,array("id"=>$id));
            if($result){
                $this->success("编辑成功","/networktype");
            }else{
                $this->error("编辑失败");
            }

        }
        
        $data['modInfo'] = $this->getNetMod();

        $info = $this->Mnetwork_type->get_one("*",array("id"=>$id));
        if(empty($info) || !isset($info)){
            die("非法参数");
        }
        $data['info'] = $info;
        
        $this->load->view("networktype/edit",$data);
    }

    /*
    * 删除
    */
    public function del($id = 0){
        $data = $this->data;
        $name = $this->Mnetwork_type->get_one("name",array("is_del"=>0,"id"=>$id));
        if(!$name){
            die("非法参数");
        }
        $where['id'] = $id;
        $list['is_del'] = 1;
        $del = $this->Mnetwork_type->update_info($list, $where);
        if($del){
            $this->success("删除成功！","/networktype");
        }else{
            $this->error("删除失败！");
        }
    }
    
    /*
     * 获取network_mod信息
     */
    public function getNetMod() {
    	$where['is_del'] =  null;
    	$list = $this->Mnetwork_mod->get_lists("*", $where);
    	if(count($list) > 0) {
    		return $list;
    	}
    	
    	return null;
    }
   
}