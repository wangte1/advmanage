<?php
/**
 * 站台管理控制器
 * 1034487709@qq.com
 */
defined('BASEPATH') or exit('No direct script access allowed');
class Mediamanage extends MY_Controller{

    public function __construct(){
        parent::__construct();
        $this->load->model([
            'Model_medias' => 'Mmedias',
            'Model_points' => 'Mpoints'
         ]);
        $this->data['code'] = 'resources_manage';
        $this->data['active'] = 'media_lists';
    }

    /*
    * 列表
    * 1034487709@qq.com
    */
    public function index(){
        $data = $this->data;
        $pageconfig = C('page.page_lists');
        $this->load->library('pagination');

        $page =  intval($this->input->get("per_page",true)) ?  : 1;
        $size = $pageconfig['per_page'];
        $where['is_del'] = 0;

        if ($this->input->get('name')) $where['like']['name'] = $this->input->get('name');
        if ($this->input->get('code')) $where['code'] = $this->input->get('code');

        if ($this->input->get('type') != 'all') {
            $where['type'] = $this->input->get('type') ? $this->input->get('type') : 1;
        }

        $data['name'] = $this->input->get('name');
        $data['med_code'] = $this->input->get('code');
        $data['type'] = $this->input->get('type');

        $data['list'] = $this->Mmedias->get_lists('*',$where,array("sort"=>"asc"),$size,($page-1)*$size);
        $data_count = $this->Mmedias->count($where);
        $data['page'] = $page;
        $data['data_count'] = $data_count;

        //获取分页
        $pageconfig['base_url'] = "/mediamanage";
        $pageconfig['total_rows'] = $data_count;
        $this->pagination->initialize($pageconfig);
        $data['pagestr'] = $this->pagination->create_links(); // 分页信息

        $data['media_type'] = C("public.media_type");
        $data['media_express_form'] = C("public.media_express_form");

        $this->load->view("media/index",$data);
    }

    /*
     * 添加
     * 1034487709@qq.com
     */
    public function  add(){
        $data = $this->data;
        $data['title'] = array("站台管理","添加站台");

        if(IS_POST){
            $post = $this->input->post();
            $post['create_user'] = $data['userInfo']['id'];
            $post['create_time'] = date("Y-m-d H:i:s");

            $post['update_user'] = $data['userInfo']['id'];
            $post['update_time'] = date("Y-m-d H:i:s");

            $result = $this->Mmedias->create($post);
            if($result){
                $this->write_log($data['userInfo']['id'],1,"新增站台：".$post['name']);
                $this->success("添加成功","/mediamanage");
            }else{
                $this->error("添加失败");
            }

        }

        $data['media_type'] = C("public.media_type");
        $data['media_express_form'] = C("public.media_express_form");


        $this->load->view("media/add",$data);
    }

    /*
     * 编辑
     * 1034487709@qq.com
     */
    public function edit($id = 0){
        $data = $this->data;
        $data['title'] = array("站台管理","添加站台");

        if(IS_POST){
            $post = $this->input->post();
            $post['update_user'] = $data['userInfo']['id'];
            $post['update_time'] = date("Y-m-d H:i:s");
            $result = $this->Mmedias->update_info($post,array("id"=>$id));
            if($result){
                $this->write_log($data['userInfo']['id'],2,"编辑站台：".$post['name']);
                $this->success("编辑成功","/mediamanage");
            }else{
                $this->error("编辑失败");
            }

        }

        $info = $this->Mmedias->get_one("*",array("id"=>$id));
        if(empty($info) || !isset($info)){
            die("非法参数");
        }
        $data['info'] = $info;

        $data['media_type'] = C("public.media_type");
        $data['media_express_form'] = C("public.media_express_form");


        $this->load->view("media/edit",$data);
    }

    /*
    * 列表
    * 1034487709@qq.com
    */
    public function del($id = 0){
        $data = $this->data;
        $name = $this->Mmedias->get_one("name",array("is_del"=>0,"id"=>$id));
        if(!$name){
            die("非法参数");
        }
        $where['id'] = $id;
        $list['is_del'] = 1;
        $del = $this->Mmedias->update_info($list, $where);
        if($del){
            $this->write_log($data['userInfo']['id'],3," 删除站台：".$name['name']);
            $this->success("删除成功！","/mediamanage");

        }else{
            $this->error("删除失败！");
        }
    }

    /*
      * 获取点位数量
      * 1034487709@qq.com
   */
    public function  get_points_nums(){
        if($this->input->is_ajax_request()){
            $id = intval($this->input->post("id"));
            $count = $this->Mpoints->count(array("media_id"=>$id,"is_del"=>0));
            if($count){
                $this->return_failed();
            }else{
                $this->return_success();
            }
        }
    }


}