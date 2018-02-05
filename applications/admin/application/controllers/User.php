<?php 
/**
* 会员管理控制器
* @author jianming@gz-zc.cn
*/
defined('BASEPATH') or exit('No direct script access allowed');
class User extends MY_Controller{

    public function __construct(){
        parent::__construct();
        $this->load->model([
             'Model_user' => 'Muser',
             'Model_user_extend' => 'Muser_extend'
        ]);
    }
    

    /**
     * 会员列表
     */
    public function index() {
        $data = $this->data;

        $pageconfig = C('page.config_log');
        $this->load->library('pagination');
        $page = $this->input->get_post('per_page') ? : '1';

        $where = array();
        if ($this->input->get('real_name')) {
            $where['like']['real_name'] = $this->input->get('real_name');
        }

        if ($this->input->get('auth_status') != "") {
            $where['auth_status'] = $this->input->get('auth_status');
        }
        $data['real_name'] = $this->input->get('real_name');
        $data['auth_status'] = $this->input->get('auth_status');

        $field = 'A.id, A.create_time, A.auth_status, A.is_limit, B.real_name, B.phone_number, B.sex, B.email';
        $users = $this->http_request('user/get_lists',['field' => $field, 'where' => $where, 'page' => ($page-1)*$pageconfig['per_page'], 'pagesize' => $pageconfig['per_page']]);
        if (isset($users['status']) && $users['status'] == 0) {
            $users = $users['data'];
        } else {
            $users = array();
        }
        $data_count = $this->http_request('user/get_count', ['where' => $where])['data'];
        $data['data_count'] = $data_count;
        $data['page'] = $page;

        //获取分页
        $pageconfig['base_url'] = "/user";
        $pageconfig['total_rows'] = $data_count;
        $this->pagination->initialize($pageconfig);
        $data['pagestr'] = $this->pagination->create_links(); // 分页信息

        $data['users'] = $users;
        $this->load->view("user/index", $data);
    }


    /**
     * 会员详情
     */
    public function info($id = 0) {
        $data = $this->data;
        $user_info = $this->http_request('user/info',['user_id' => $id]);
        if (isset($user_info['status']) && $user_info['status'] == 0) {
            $data['info'] = $user_info['data']['user_info'];
        }
        $data['education'] = array_column(C('user_center.education'),'name','id');
        $data['occupation'] = array_column(C('user_center.occupation'),'name','id');
        $this->load->view('user/info', $data);
    }


    /**
     * 审核
     */
    public function audit() {
        $user_id = $this->input->post("user_id");
        $auth_status = $this->input->post("auth_status");

        //站内消息
        if ($auth_status == 2) {    
            $title = C('message.identity.yes.title');
            $content = C('message.identity.yes.content');
        } else {      
            $title = C('message.identity.no.title');
            $content = str_replace('__remark__', $this->input->post('remark'), C('message.identity.no.content'));
        }

        $remark = $this->input->post("remark");
        $result = $this->http_request('user/update', ['data' => array('auth_status' => $auth_status), 'id' => $user_id]);
        if(isset($result['status']) && $result['status'] == 0) {
            //更新t_user_extend表
            $this->http_request('user/update_extend', ['data' => array('remark' => $remark), 'user_id' => $user_id]);
            
            //写入站内消息
            $this->http_request('message/add', ['data' => array('title' => $title, 'content' => $content, 'receiver' => $user_id)]);

            $this->return_json(array('flag' => true, 'msg'=>'保存成功'));
        } else {
            $this->return_json(array('flag' => false, 'msg'=>'保存失败'));
        }
    }


    /**
     * 登录限制
     */
    public function set_login($id, $state) {
        $result = $this->http_request('user/update', ['data' => array('is_limit' => $state), 'id' => $id]);
        if(isset($result['status']) && $result['status'] == 0) {
            $this->success("操作成功！", "/user");
        } else {
            $this->success("操作失败！", "/user");
        }
    }
}
?>
