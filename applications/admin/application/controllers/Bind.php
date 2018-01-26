<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Bind extends MY_Controller{

    public function __construct(){
        parent::__construct();
    }
    
    public function index(){
        require_once '../../../workerman/gatewayWorker/Gateway.php';
        $client_id = $this->input->post('client_id');
        // 设置GatewayWorker服务的Register服务ip和端口，请根据实际情况改成实际值
        Gateway::$registerAddress = '127.0.0.1:1238';
        // 获取用户id
        $uid = (int) $_SESSION['USER']['id'];
        $role_id = (int) $_SESSION['USER']['group_id'];
        $group_id = date('Y');//用为全站广播组
        // client_id与uid绑定
        Gateway::bindUid($client_id, $uid);
        // 加入某个群组（可调用多次加入多个群组）
        Gateway::joinGroup($client_id, $group_id);
        // 加入角色群组
        Gateway::joinGroup($client_id, $role_id);
    }
}