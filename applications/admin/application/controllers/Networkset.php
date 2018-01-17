<?php
/**
 * 排班设置控制器
 */
defined('BASEPATH') or exit('No direct script access allowed');
class Networkset extends MY_Controller{

    public function __construct(){
        parent::__construct();
        $this->load->model([
        	'Model_network_base' => 'Mnetwork_base',
        	'Model_network_type' => 'Mnetwork_type',
        	'Model_network_mod' => 'Mnetwork_mod'
         ]);
        $this->data['code'] = 'net_manage';
        $this->data['active'] = 'network_set_list';
        $this->load->driver('cache');
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
        	//$where['mod'] = (int)$this->input->get('mod');
        	$mod = (int)$this->input->get('mod');
        }else {
        	$mod = 1;
        	//$where['mod'] = 1;
        }
        $idsArr = $this->getNetTypeIds($mod);
        $newArr = array();
        if(count($idsArr)) {
        	foreach ($idsArr as $k => $v) {
        		$newArr[] = (int)$v['id'];
        	}
        }
        
        $where['in']['type'] = $newArr;
        
        $data['mod'] = $mod;

        $data['list'] = $this->Mnetwork_base->get_lists('*',$where,array("sort"=>"asc"),$size,($page-1)*$size);
        $data_count = $this->Mnetwork_base->count($where);
        $data['page'] = $page;
        $data['data_count'] = $data_count;

        //获取分页
        $pageconfig['base_url'] = "/networkset";
        $pageconfig['total_rows'] = $data_count;
        $this->pagination->initialize($pageconfig);
        $data['pagestr'] = $this->pagination->create_links(); // 分页信息
        
        $data['nettype'] = $this->getType();
        
        $data['modInfo'] = $this->getNetMod();
        
        //var_dump($this->getType());

        $this->load->view("networkset/index",$data);
    }
    
    /*
     * 根据mod获取network_type的基本信息，mod=1代表腾讯，mod=2代表凤凰
     */
    public function getNetType() {
    	
    }

    /*
     * 添加
     */
    public function  add(){
        $data = $this->data;
        $data['title'] = array("站台管理","添加站台");

        if(IS_POST){
            $post = $this->input->post();
            $result = $this->Mnetwork_base->create($post);
            if($result){
            	$this->cache->file->delete('netbase');
                $this->success("添加成功","/networkset");
            }else{
                $this->error("添加失败");
            }

        }
        
        $data['nettype'] = $this->getType();
        $data['modInfo'] = $this->getNetMod();

        $this->load->view("networkset/add",$data);
    }

    /*
     * 编辑
     */
    public function edit($id = 0){
        $data = $this->data;
        $data['title'] = array("站台管理","添加站台");

        if(IS_POST){
            $post = $this->input->post();
            $result = $this->Mnetwork_base->update_info($post,array("id"=>$id));
            if($result){
            	$this->cache->file->delete('netbase');
                $this->success("编辑成功","/networkset");
            }else{
                $this->error("编辑失败");
            }

        }

        $info = $this->Mnetwork_base->get_one("*",array("id"=>$id));
        if(empty($info) || !isset($info)){
            die("非法参数");
        }
        $data['info'] = $info;
        
        $data['nettype'] = $this->getType();

        $this->load->view("networkset/edit",$data);
    }

    /*
    * 删除
    */
    public function del($id = 0){
        $data = $this->data;
        $name = $this->Mnetwork_base->get_one("name",array("is_del"=>null,"id"=>$id));
        if(!$name){
            die("非法参数");
        }
        $where['id'] = $id;
        $list['is_del'] = 1;
        $del = $this->Mnetwork_base->update_info($list, $where);
        if($del){
        	$this->cache->file->delete('netbase');
            $this->success("删除成功！","/networkset");
        }else{
            $this->error("删除失败！");
        }
    }
	
    
    public function getType() {
    	$where['is_del'] = null;
    	$list = $this->Mnetwork_type->get_lists('*',$where);
    	if($list) {
    		return $list;
    	}else {
    		return null;
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
    
    /*
     * 根据mod获取network_type的id的集合
     */
    public function getNetTypeIds($mod) {
    	$where['is_del'] = null;
    	$where['mod'] = $mod;
    	$list = $this->Mnetwork_type->get_lists('id',$where);
    	if($list) {
    		return $list;
    	}else {
    		return null;
    	}
    }
    
   


}