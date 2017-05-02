<?php
/**
 * 客户管理控制器
 * 1034487709@qq.com
 */
defined('BASEPATH') or exit('No direct script access allowed');
class Customers extends MY_Controller{

    public function __construct(){
        parent::__construct();
        $this->load->model([
            'Model_customers' => 'Mcustomers',
            'Model_customer_project' => 'Mcustomer_project',
            'Model_points' => 'Mpoints',
         ]);
        $this->pageconfig = C('page.page_lists');
        $this->load->library('pagination');
        $this->data['code'] = 'resources_manage';
        $this->data['active'] = 'customers_list';
    }

    /*
    * 列表
    * 1034487709@qq.com
    */
    public function index(){
        $data = $this->data;
        $data['title'] = array("客户管理管理","客户列表");

        $page =  intval($this->input->get("per_page",true)) ?  : 1;
        $size = $this->pageconfig['per_page'];
        $where['is_del'] = 0;
        $url = "/customers/index?ser=1";

        $data['name'] = trim($this->input->get('name'));
        if ($this->input->get('name')) {
            $where['like']['customer_name'] = $data['name'];
            $data['name'] = $data['name'];
            $url.="&name=".$data['name'];
        }

         //类型
        $data['type'] = $this->input->get('type');
        if($this->input->get('type')){
            $where['type'] = $data['type'];
            $url.="&type=".$data['type'];
        }



        $data['list'] = $this->Mcustomers->get_lists('*',$where,array("id"=>"desc"),$size,($page-1)*$size);

        $data_count = $this->Mcustomers->count($where);

        //获取分页
        $data['pagestr'] = "";
        if(! empty($data['list'])){
            $this->pageconfig['base_url'] = $url;
            $this->pageconfig['total_rows'] = $data_count;
            $this->pagination->initialize($this->pageconfig);
            $data['pagestr'] = $this->pagination->create_links(); // 分页信息
        }
        $data['page'] = $page;
        $data['data_count'] = $data_count;

        $data['customer_type'] = C("public.customer_type");

        $this->load->view("customers/index",$data);
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

            $id = $this->Mcustomers->create($post);
            if($id){
                $this->write_log($data['userInfo']['id'],1,"新增客户：".$post['customer_name']);
                $this->success("添加成功","/customers");
            }else{
                $this->error("添加失败");
            }

        }

        $data['customer_type'] = C("public.customer_type");
        $this->load->view("customers/add",$data);
    }

    /*
     * 编辑
     * 1034487709@qq.com
     */
    public function edit($id = 0){
        $data = $this->data;
        $data['title'] = array("客户管理","编辑客户");

        if(IS_POST){
            $post = $this->input->post();
            $post['update_user'] = $data['userInfo']['id'];
            $post['update_time'] = date("Y-m-d H:i:s");

            $result = $this->Mcustomers->update_info($post,array("id"=>$id));
            if($result){
                $this->write_log($data['userInfo']['id'],2,"编辑客户：".$post['customer_name']);
                $this->success("编辑成功","/customers");
            }else{
                $this->error("编辑失败");
            }

        }

        $info = $this->Mcustomers->get_one("customer_name,contact_man,contact_mobile,type,id,remark,address",array("id"=>$id));
        if(empty($info) || !isset($info)){
            die("非法参数");
        }

        $data['info'] = $info;

        $data['customer_type'] = C("public.customer_type");
        $this->load->view("customers/edit",$data);
    }

    /*
    * 列表
    * 1034487709@qq.com
    */
    public function del($id = 0){
        $data = $this->data;
        $data['title'] = array("客户管理","删除删除");
        $customer_name = $this->Mcustomers->get_one("customer_name",array("is_del"=>0,"id"=>$id));
        if(!$customer_name){
            die("非法参数");
        }
        $where['id'] = $id;
        $list['is_del'] = 1;
        $del = $this->Mcustomers->update_info($list, $where);
        if($del){
            //删除该客户下的所有项目
            $this->Mcustomer_project->delete(array('customer_id' => $id));

            $this->write_log($data['userInfo']['id'],3," 删除客户：".$customer_name['customer_name']);
            $this->success("删除成功!!","/customers");

        }else{
            $this->error("删除失败!!");
        }
    }

    /*
     * 获取点位数量
     * 1034487709@qq.com
    */
    public function get_customer(){
        if($this->input->is_ajax_request()){
            $customer_id = intval($this->input->post("id"));

            //判断该用户是否预定点位
            $count = $this->Mpoints->count(array("customer_id"=>$customer_id,"is_del"=>0));
            if($count){
                $this->return_failed();
            }else{
                $this->return_success();
            }
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
                $this->return_json(array("nums"=>$count,"code"=>1));
            }else{
                $this->return_json(array("nums"=>$count,"code"=>2));
            }
        }
    }


}