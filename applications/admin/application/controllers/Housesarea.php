<?php
/**
 * 楼盘区域管理控制器
 * 867332352@qq.com
 */
defined('BASEPATH') or exit('No direct script access allowed');
class Housesarea extends MY_Controller{

    public function __construct(){
        parent::__construct();
        $this->load->model([
        	'Model_houses' => 'Mhouses',
            'Model_houses_area' => 'Mhouses_area'
         ]);
        $this->data['code'] = 'community_manage';
        $this->data['active'] = 'houses_area_lists';
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

        $data['list'] = $this->Mhouses_area->get_lists('*',$where,[],$size,($page-1)*$size);
        $data_count = $this->Mhouses_area->count($where);
        $data['page'] = $page;
        $data['data_count'] = $data_count;

        //获取分页
        $pageconfig['base_url'] = "/houses";
        $pageconfig['total_rows'] = $data_count;
        $this->pagination->initialize($pageconfig);
        $data['pagestr'] = $this->pagination->create_links(); // 分页信息
		
        $data['houses_type'] = C("public.houses_type");
        $where = [];
        $where['is_del'] = 0;
        $data['list1'] = $this->Mhouses->get_lists('id,name',$where);
        
        $this->load->view("housesarea/index",$data);
    }

    /*
     * 添加
     * 867332352@qq.com
     */
    public function  add(){
        $data = $this->data;
        $data['title'] = array("楼盘区域管理","添加楼盘区域");

        if(IS_POST){
            $post = $this->input->post();
            $post['creator'] = $data['userInfo']['id'];
            $post['create_time'] = date("Y-m-d H:i:s");

            $result = $this->Mhouses_area->create($post);
            if($result){
                $this->write_log($data['userInfo']['id'],1,"新增楼盘区域：".$post['name']);
                $this->success("添加成功","/housesarea");
            }else{
                $this->error("添加失败");
            }

        }
        
        $where['is_del'] = 0;
        $data['list'] = $this->Mhouses->get_lists('id,name',$where);

        $this->load->view("housesarea/add",$data);
    }

    /*
     * 编辑
     * 867332352@qq.com
     */
    public function edit($id = 0){
        $data = $this->data;
        $data['title'] = array("楼盘区域管理","编辑楼盘区域");

        if(IS_POST){
            $post = $this->input->post();
            //$post['update_user'] = $data['userInfo']['id'];
            //$post['update_time'] = date("Y-m-d H:i:s");
            $result = $this->Mhouses_area->update_info($post,array("id"=>$id));
            if($result){
                $this->write_log($data['userInfo']['id'],2,"编辑站台：".$post['name']);
                $this->success("编辑成功","/housesarea");
            }else{
                $this->error("编辑失败");
            }

        }

        $info = $this->Mhouses_area->get_one("*",array("id"=>$id));
        if(empty($info) || !isset($info)){
            die("非法参数");
        }
        $data['info'] = $info;

        $where['is_del'] = 0;
        $data['list'] = $this->Mhouses->get_lists('id,name',$where);


        $this->load->view("housesarea/edit",$data);
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
        $del = $this->Mhouses_area->update_info($list, $where);
        if($del){
            $this->write_log($data['userInfo']['id'],3," 删除楼盘区域：".$name['name']);
            $this->success("删除成功！","/housesarea");

        }else{
            $this->error("删除失败！");
        }
    }


}