<?php 
/**
* 规格管理控制器
* @author jianming@gz-zc.cn
*/
defined('BASEPATH') or exit('No direct script access allowed');
class Specification extends MY_Controller{

    public function __construct(){
        parent::__construct();
        $this->load->model([
             'Model_specifications' => 'Mspecifications',
             'Model_admins' => 'Madmins',
        ]);
        $this->data['code'] = 'resources_manage';
        $this->data['active'] = 'specification_list';
    }
    

    /**
     * 规格列表
     */
    public function index() {
        $data = $this->data;
        $pageconfig = C('page.page_lists');
        $this->load->library('pagination');
        $page = $this->input->get_post('per_page') ? : '1';

        $where['is_del'] =  0;
        if ($this->input->get('code')) $where['code'] = $this->input->get('code');

        if ($this->input->get('name')) $where['like']['name'] = $this->input->get('name');

        if ($this->input->get('is_del')) $where['is_del'] = $this->input->get('is_del');

        $data['name'] = $this->input->get('name');
        $data['is_del'] = $this->input->get('is_del');

        $data['list'] = $this->Mspecifications->get_lists("*", $where, array("create_time" => "DESC"), $pageconfig['per_page'], ($page-1)*$pageconfig['per_page']);
        $data_count = $this->Mspecifications->count($where);
        $data['data_count'] = $data_count;
        $data['page'] = $page;

        //获取分页
        $pageconfig['base_url'] = "/specification/index/";
        $pageconfig['total_rows'] = $data_count;
        $this->pagination->initialize($pageconfig);
        $data['pagestr'] = $this->pagination->create_links(); // 分页信息

        $admins = $this->Madmins->get_lists("id,name");
        $data['admins'] = array_column($admins,"name","id");
        $this->load->view("specification/index", $data);
    }


    /**
     * 添加规格
     */
    public function add() {
        $data = $this->data;
        if (IS_POST) {
            $post_data = $this->input->post();
            $post_data['create_user'] = $post_data['update_user'] = $data['userInfo']['id'];
            $post_data['create_time'] = $post_data['update_time'] = date('Y-m-d H:i:s');
            $id = $this->Mspecifications->create($post_data);
            if ($id) {
                $this->success("添加成功！","/specification");
            } else {
                $this->success("添加失败！","/specification");
            }
        } else {
            $this->load->view("specification/add", $data);
        }
    }




    /* 
     * 编辑规格
     */
    public function edit($id) {
        $data = $this->data;
        if (IS_POST) {
            $post_data = $this->input->post();
            $post_data['update_user'] = $data['userInfo']['id'];
            $post_data['update_time'] = date('Y-m-d H:i:s');
            $id = $this->Mspecifications->update_info($post_data, array('id' => $id));
            if ($id) {
                $this->success("修改成功！","/specification");
            } else {
                $this->success("修改失败！请重试！","/specification");
            }
        } else {
            $data['info'] = $this->Mspecifications->get_one("*", array('id' => $id));
            $this->load->view("specification/add", $data);
        }
    }


    /*
     * 删除和恢复规格
     */
    public function del($id, $state) {
        $res = $this->Mspecifications->update_info(array('is_del' => $state), array('id' => $id));
        if ($res) {
            $this->success("操作成功！", "/specification");
        } else {
            $this->success("操作失败！请重试！", "/specification");
        }
    }

}

