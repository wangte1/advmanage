<?php
/**
 * 点位规格控制器
 * 867332352@qq.com
 */
defined('BASEPATH') or exit('No direct script access allowed');
class Housesformat extends MY_Controller{

    public function __construct(){
        parent::__construct();
        $this->load->model([
            'Model_houses_points_format' => 'Mhouses_points_format',
         ]);
        $this->data['code'] = 'community_manage';
        $this->data['active'] = 'points_type_list';
        
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

        if ($this->input->get('type')) $where['type'] = $this->input->get('type');

        $data['type'] = $this->input->get('type');

        $data['list'] = $this->Mhouses_points_format->get_lists('*',$where,[],$size,($page-1)*$size);
        $data_count = $this->Mhouses_points_format->count($where);
        $data['page'] = $page;
        $data['data_count'] = $data_count;
        

        //获取分页
        $pageconfig['base_url'] = "/housespoints";
        $pageconfig['total_rows'] = $data_count;
        $this->pagination->initialize($pageconfig);
        $data['pagestr'] = $this->pagination->create_links(); // 分页信息
		
        $this->load->view("housesformat/index",$data);
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

            $result = $this->Mhouses_points_format->create($post);
            if($result){
                $this->write_log($data['userInfo']['id'],1,"新增社区点位规格：".$post['type']);
                $this->success("添加成功","/housesformat",1);
            }else{
                $this->error("添加失败",'',1);
            }

        }
       	
        $this->load->view("housesformat/add",$data);
    }

    /*
     * 编辑
     * 867332352@qq.com
     */
    public function edit($id = 0){
        $data = $this->data;
        //$data['title'] = array("站台管理","添加站台");

        if(IS_POST){
            $post = $this->input->post();
            $result = $this->Mhouses_points_format->update_info($post,array("id"=>$id));
            if($result){
                $this->write_log($data['userInfo']['id'],2,"编辑社区点位规格：".$post['type']);
                $this->success("编辑成功","/housesformat",1);
            }else{
                $this->error("编辑失败",'',1);
            }

        }

        $info = $this->Mhouses_points_format->get_one("*",array("id"=>$id));
        if(empty($info) || !isset($info)){
            die("非法参数");
        }
        $data['info'] = $info;

        $this->load->view("housesformat/edit",$data);
    }

    /*
    * 列表
    * 867332352@qq.com
    */
    public function del($id = 0){
        $data = $this->data;
        $type = $this->Mhouses_points_format->get_one("type",array("is_del"=>0,"id"=>$id));
        if(!$type){
            die("非法参数");
        }
        $where['id'] = $id;
        $list['is_del'] = 1;
        $del = $this->Mhouses_points_format->update_info($list, $where);
        if($del){
            $this->write_log($data['userInfo']['id'],3," 删除点位类型：".$name['type']);
            $this->success("删除成功！","/pointstype");

        }else{
            $this->error("删除失败！");
        }
    }

}