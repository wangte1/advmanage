<?php
/**
 * 业务员管理控制器
 * @author 1034487709@qq.com
 */
defined('BASEPATH') or exit('No direct script access allowed');
class Salesman extends MY_Controller{

    public function __construct(){
        parent::__construct();
        $this->load->model([
            'Model_salesman' => 'Msalesman'
        ]);
        $this->data['code'] = 'resources_manage';
        $this->data['active'] = 'salesman_list';
    }


    /**
     * 业务员列表
     * 1034487709@qq.com
     */
    public function index() {
        $data = $this->data;
        $pageconfig = C('page.page_lists');
        $this->load->library('pagination');
        $page = $this->input->get_post('per_page') ? : '1';

        $where['is_del'] =  0;
        if ($this->input->get('name')) $where['like']['name'] = $this->input->get('name');

        if ($this->input->get('is_del')) $where['is_del'] = $this->input->get('is_del');

        $data['name'] = $this->input->get('name');
        $data['is_del'] = $this->input->get('is_del');

        $data['list'] = $this->Msalesman->get_lists("*", $where, array("create_time" => "DESC"), $pageconfig['per_page'], ($page-1)*$pageconfig['per_page']);
        $data_count = $this->Msalesman->count($where);
        $data['data_count'] = $data_count;
        $data['page'] = $page;

        //获取分页
        $pageconfig['base_url'] = "/Salesman";
        $pageconfig['total_rows'] = $data_count;
        $this->pagination->initialize($pageconfig);
        $data['pagestr'] = $this->pagination->create_links(); // 分页信息

        $admins = $this->Madmins->get_lists("id,name");
        $data['admins'] = array_column($admins,"name","id");
        $this->load->view("salesman/index", $data);
    }


    /**
     * 添加业务员
     *  1034487709@qq.com
     */
    public function add() {
        $data = $this->data;
        if (IS_POST) {
            $post_data = $this->input->post();

            $post_data['create_user'] = $post_data['update_user'] = $data['userInfo']['id'];
            $post_data['create_time'] = $post_data['update_time'] = date('Y-m-d H:i:s');
            $id = $this->Msalesman->create($post_data);
            if ($id) {
                $this->success("添加成功！","/salesman");
            } else {
                $this->success("添加失败！","/salesman");
            }
        } else {
            $this->load->view("salesman/add", $data);
        }
    }




    /**
     *  编辑业务员
     *  1034487709@qq.com
     */
    public function edit($id) {
        $data = $this->data;
        if (IS_POST) {
            $post_data = $this->input->post();
            $post_data['update_user'] = $data['userInfo']['id'];
            $post_data['update_time'] = date('Y-m-d H:i:s');

            $id = $this->Msalesman->update_info($post_data, array('id' => $id));
            if ($id) {
                $this->success("修改成功！","/salesman");
            } else {
                $this->success("修改失败！请重试！","/salesman");
            }
        } else {
            $data['info'] = $this->Msalesman->get_one("*", array('id' => $id));
            $this->load->view("salesman/add", $data);
        }
    }


    /*
     * 删除和恢复业务员
     */
    public function del($id, $state) {
        $res = $this->Msalesman->update_info(array('is_del' => $state), array('id' => $id));
        if ($res) {
            $this->success("操作成功！", "/salesman");
        } else {
            $this->success("操作失败！请重试！", "/salesman");
        }
    }

}

