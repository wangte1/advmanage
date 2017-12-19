<?php
/**
 * 客户管理控制器
 * jianming
 */
defined('BASEPATH') or exit('No direct script access allowed');
class Customerprojects extends MY_Controller{

    public function __construct(){
        parent::__construct();
        $this->load->model([
            'Model_customers' => 'Mcustomers',
            'Model_customer_project' => 'Mcustomer_project',
         ]);
        $this->data['code'] = 'resources_manage';
        $this->data['active'] = 'customer_projects_list';
        $this->data['customers_list'] = $this->Mcustomers->get_lists("id,customer_name", array('is_del' => 0)); //客户列表
    }

    /*
    * 客户项目列表
    */
    public function index(){
        $data = $this->data;
        $pageconfig = C('page.page_lists');
        $this->load->library('pagination');
        $page = $this->input->get_post('per_page') ? : '1';

        $where['is_del'] =  0;
        if ($this->input->get('project_name')) $where['like']['project_name'] = $this->input->get('project_name');

        if ($this->input->get('customer_id')) $where['customer_id'] = $this->input->get('customer_id');

        if ($this->input->get('is_del')) $where['is_del'] = $this->input->get('is_del');

        $data['project_name'] = $this->input->get('project_name');
        $data['customer_id'] = $this->input->get('customer_id');
        $data['is_del'] = $this->input->get('is_del');

        $data['list'] = $this->Mcustomer_project->get_lists("*", $where, array("create_time" => "DESC"), $pageconfig['per_page'], ($page-1)*$pageconfig['per_page']);
        $data_count = $this->Mcustomer_project->count($where);
        $data['data_count'] = $data_count;
        $data['page'] = $page;

        //获取分页
        $pageconfig['base_url'] = "/customerprojects/index/";
        $pageconfig['total_rows'] = $data_count;
        $this->pagination->initialize($pageconfig);
        $data['pagestr'] = $this->pagination->create_links(); // 分页信息

        //客户
        $data['customers'] = array_column($data['customers_list'], "customer_name", "id");

        //创建人
        $admins = $this->Madmins->get_lists("id,fullname");
        $data['admins'] = array_column($admins,"fullname","id");

        $this->load->view("projects/index", $data);
    }

    /*
     * 添加项目
     */
    public function  add(){
        $data = $this->data;
        if (IS_POST) {
            $post_data = $this->input->post();
            $post_data['create_user'] = $post_data['update_user'] = $data['userInfo']['id'];
            $post_data['create_time'] = $post_data['update_time'] = date('Y-m-d H:i:s');
            $id = $this->Mcustomer_project->create($post_data);
            if ($id) {
                $this->write_log($data['userInfo']['id'], 1, "新增客户项目".$post_data['project_name']);
                $this->success("添加成功！","/customerprojects");
            } else {
                $this->error("添加失败！");
            }
        } else {
            $this->load->view("projects/add", $data);
        }
    }

    /*
     * 修改项目
     */
    public function edit($id = 0){
        $data = $this->data;
        if (IS_POST) {
            $post_data = $this->input->post();
            $post_data['update_user'] = $data['userInfo']['id'];
            $post_data['update_time'] = date('Y-m-d H:i:s');
            $id = $this->Mcustomer_project->update_info($post_data, array('id' => $id));
            if ($id) {
                $this->write_log($data['userInfo']['id'], 2, "修改客户项目，项目id".$id);
                $this->success("修改成功！","/customerprojects");
            } else {
                $this->error("修改失败！请重试！");
            }
        } else {
            $data['info'] = $this->Mcustomer_project->get_one("*", array('id' => $id));

            //所属客户
            $data['info']['customer_name'] = $this->Mcustomers->get_one('customer_name', array('id' => $data['info']['customer_id']))['customer_name'];

            $this->load->view("projects/add", $data);
        }
    }


    /*
     * 删除项目
     */
    public function del($id, $state) {
        $res = $this->Mcustomer_project->update_info(array('is_del' => $state), array('id' => $id));
        if ($res) {
            $this->success("操作成功！", "/customerprojects");
        } else {
            $this->success("操作失败！请重试！", "/customerprojects");
        }
    }

}