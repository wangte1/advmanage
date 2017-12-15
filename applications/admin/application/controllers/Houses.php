<?php
/**
 * 楼盘管理控制器
 * 867332352@qq.com
 */
defined('BASEPATH') or exit('No direct script access allowed');
class Houses extends MY_Controller{

    public function __construct(){
        parent::__construct();
        $this->load->model([
            'Model_houses' => 'Mhouses',
        	'Model_area' => 'Marea'
         ]);
        $this->data['code'] = 'community_manage';
        $this->data['active'] = 'houses_list';
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
        if ($this->input->get('province')) $where['province'] = $this->input->get('province');
        if ($this->input->get('city')) $where['city'] = $this->input->get('city');
        if ($this->input->get('area')) $where['area'] = $this->input->get('area');

        if ($this->input->get('type') != 'all' && !empty($this->input->get('type'))) {
            $where['type'] = $this->input->get('type') ? $this->input->get('type') : 1;
        }

        $data['name'] = $this->input->get('name');
        $data['type'] = $this->input->get('type');
        $data['province'] = $this->input->get('province');
        $data['city'] = $this->input->get('city');
        $data['area'] = $this->input->get('area');

        $data['list'] = $this->Mhouses->get_lists('*',$where,[],$size,($page-1)*$size);
        $data_count = $this->Mhouses->count($where);
        $data['page'] = $page;
        $data['data_count'] = $data_count;

        //获取分页
        $pageconfig['base_url'] = "/houses";
        $pageconfig['total_rows'] = $data_count;
        $this->pagination->initialize($pageconfig);
        $data['pagestr'] = $this->pagination->create_links(); // 分页信息
		
        $data['houses_type'] = C("public.houses_type");
        
        $this->load->view("houses/index",$data);
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

            $result = $this->Mhouses->create($post);
            if($result){
                $this->write_log($data['userInfo']['id'],1,"新增楼盘：".$post['name']);
                $this->success("添加成功","/houses");
            }else{
                $this->error("添加失败");
            }

        }

        //获取省级
        $data['province'] = $this->Marea->get_lists("id,area_name,parent_id,level",array("parent_id"=>0));
        //城市
        $data['city'] = $this->Marea->get_lists("id,area_name,parent_id,level",array("parent_id"=>35560));
        //地区
        $data['area'] = $this->Marea->get_lists("id,area_name,parent_id,level",array("parent_id"=>35561));
        
        $data['houses_type'] = C("public.houses_type");

        $this->load->view("houses/add",$data);
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
            //$post['update_user'] = $data['userInfo']['id'];
            //$post['update_time'] = date("Y-m-d H:i:s");
            $result = $this->Mhouses->update_info($post,array("id"=>$id));
            if($result){
                $this->write_log($data['userInfo']['id'],2,"编辑楼盘：".$post['name']);
                $this->success("编辑成功","/houses");
            }else{
                $this->error("编辑失败");
            }

        }

        $info = $this->Mhouses->get_one("*",array("id"=>$id));
        if(empty($info) || !isset($info)){
            die("非法参数");
        }
        $data['info'] = $info;
		
        
        //获取省级
        $data['province'] = $this->Marea->get_lists("id,area_name,parent_id,level",array("parent_id"=>0));
        //城市
        $data['city'] = $this->Marea->get_lists("id,area_name,parent_id,level",array("parent_id"=>35560));
        //地区
        $data['area'] = $this->Marea->get_lists("id,area_name,parent_id,level",array("parent_id"=>35561));
        
        $data['houses_type'] = C("public.houses_type");

        $this->load->view("houses/edit",$data);
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


}