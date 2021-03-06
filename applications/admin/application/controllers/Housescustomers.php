<?php
/**
 * 客户管理控制器
 * 867332352@qq.com
 */
defined('BASEPATH') or exit('No direct script access allowed');
class Housescustomers extends MY_Controller{

    public function __construct(){
        parent::__construct();
        $this->load->model([
            'Model_houses_customers' => 'Mhouses_customers',
            'Model_admins' => 'Madmin',
         ]);
        $this->pageconfig = C('page.page_lists');
        $this->load->library('pagination');
        $this->data['code'] = 'community_manage';
        $this->data['active'] = 'houses_customers_list';
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
        $url = "/housescustomers/index?ser=1";

        $data['name'] = trim($this->input->get('name'));
        if ($this->input->get('name')) {
            $where['like']['name'] = $data['name'];
            $data['name'] = $data['name'];
            $url.="&name=".$data['name'];
        }

         //类型
        $data['type'] = $this->input->get('type');
        if($this->input->get('type')){
            $where['type'] = $data['type'];
            $url.="&type=".$data['type'];
        }

        $data['list'] = $this->Mhouses_customers->get_lists('*',$where,array("id"=>"desc"),$size,($page-1)*$size);
        $data_count = $this->Mhouses_customers->count($where);
        $res = $this->Madmin->get_lists('*',['group_id' => '2','is_del' => 1]);
        $data['admin'] = $res;
        $res1 = $this->Madmin->get_lists('*',['is_del' => 1]);
        $data['sadmin'] = $res1;

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

        $data['customer_type'] = C("public.houses_customer_type");
        $data['enterprise_type'] = C('public.enterprise_type');
        $data['customer_type2'] = C('public.customer_type2');
        foreach ($data['list'] as $k => $v){
            foreach ($data['enterprise_type'] as $k1 => $v1){
                if($v['enterprise_type'] == $k1){
                    $data['list'][$k]['enterprise_type'] = $v1;
                }
            }
            foreach ($data['customer_type'] as $k2 => $v2){
                if($v['customer_type'] == $k2){
                    $data['list'][$k]['customer_type'] = $v2;
                }
            }
            foreach ($data['admin'] as $k3 => $v3){
                $data['list'][$k]['salesman_fullname'] = '';
                if($v['salesman_id'] == $v3['id']){
                    $data['list'][$k]['salesman_fullname'] = $v3['fullname'];
                    break;
                }
            }
            foreach ($data['sadmin'] as $k4 => $v4){
                $data['list'][$k]['check_name'] = '';
                if($v['check_id'] == $v4['id']){
                    $data['list'][$k]['check_name'] = $v4['fullname'];
                    break;
                }
            }
        }

        $this->load->view("housescustomers/index",$data);
    }

    /*
     * 添加
     * 1034487709@qq.com
     */
    public function  add(){
        $data = $this->data;
        $data['title'] = array("客户管理","添加客户");

        if(IS_POST){
            $post = $this->input->post();
            if(isset($post['email']) && !empty($post['email'])){
                if(!preg_match(C('regular_expression.email'), $post['email'])){
                    $this->error("电子邮件格式不正确");
                }
            }
            $post['creator'] = $data['userInfo']['id'];
            $post['create_time'] = date("Y-m-d H:i:s");

            $id = $this->Mhouses_customers->create($post);
            if($id){
                $this->write_log($data['userInfo']['id'],1,"新增社区客户：".$post['name']);
                $this->success("添加成功","/housescustomers");
            }else{
                $this->error("添加失败");
            }

        }
        $res = $this->Madmin->get_lists('*',['group_id' => '2','is_del' => 1]);
        $data['admin'] = $res;
        $data['customer_type'] = C("public.houses_customer_type");
        $data['enterprise_type'] = C('public.enterprise_type');
        $data['customer_type2'] = C('public.customer_type2');
        $this->load->view("housescustomers/add",$data);
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
            if(isset($post['email']) && !empty($post['email'])){
                if(!preg_match(C('regular_expression.email'), $post['email'])){
                    $this->error("电子邮件格式不正确");
                }
            }
            $result = $this->Mhouses_customers->update_info($post,array("id"=>$id));
            if($result){
                $this->write_log($data['userInfo']['id'],2,"编辑社区客户：".$post['name']);
                $this->success("编辑成功","/housescustomers");
            }else{
                $this->error("编辑失败");
            }

        }

        $info = $this->Mhouses_customers->get_one("*",array("id"=>$id));
        if(empty($info) || !isset($info)){
            die("非法参数");
        }
        $res = $this->Madmin->get_lists('*',['group_id' => '2','is_del' => 1]);
        $data['admin'] = $res;

        $data['info'] = $info;

        $data['customer_type'] = C("public.houses_customer_type");
        $data['enterprise_type'] = C('public.enterprise_type');
        $data['customer_type2'] = C('public.customer_type2');
        $this->load->view("housescustomers/edit",$data);
    }

    /*
    * 列表
    * 1034487709@qq.com
    */
    public function del($id = 0){
        $data = $this->data;
        $data['title'] = array("客户管理","删除删除");
        $customer_name = $this->Mhouses_customers->get_one("name",array("is_del"=>0,"id"=>$id));
        if(!$customer_name){
            die("非法参数");
        }
        $where['id'] = $id;
        $list['is_del'] = 1;
        $del = $this->Mhouses_customers->update_info($list, $where);
        if($del){
            
            $this->write_log($data['userInfo']['id'],3," 删除社区客户：".$customer_name['name']);
            $this->success("删除成功!!","/housescustomers");

        }else{
            $this->error("删除失败!!");
        }
    }

}