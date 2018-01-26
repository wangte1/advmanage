<?php 
/**
* 订单管理控制器
* @author jianming
*/
defined('BASEPATH') or exit('No direct script access allowed');
class Scheduledorders extends MY_Controller{

    public function __construct(){
        parent::__construct();
        $this->load->model([
             'Model_scheduled_orders' => 'Mscheduled_orders',
             'Model_admins' => 'Madmins',
             'Model_medias' => 'Mmedias',
             'Model_customers' => 'Mcustomers',
             'Model_points' => 'Mpoints'
        ]);
        $this->data['code'] = 'orders_manage';
        $this->data['active'] = 'scheduled_order_list';

        $this->data['medias'] = $this->Mmedias->get_lists("id, code, name", array('is_del' => 0), array('sort' => 'asc'));  //媒体
        $this->data['customers'] = $this->Mcustomers->get_lists("id, customer_name", array('is_del' => 0));  //客户
        $this->data['order_type_text'] = C('order.order_type'); //订单类型
    }
    

    /**
     * 订单列表
     */
    public function index() {
        $data = $this->data;
        $pageconfig = C('page.page_lists');
        $this->load->library('pagination');
        $page = $this->input->get_post('per_page') ? : '1';

        $where =  array();
        if ($this->input->get('order_type')) $where['A.order_type'] = $this->input->get('order_type');
        if ($this->input->get('customer_id')) $where['A.customer_id'] = $this->input->get('customer_id');
        if ($this->input->get('admin_id')) $where['C.id'] = $this->input->get('admin_id');
        if ($this->input->get('order_status')) $where['A.order_status'] = $this->input->get('order_status');

        $data['order_type'] = $this->input->get('order_type');
        $data['customer_id'] = $this->input->get('customer_id');
        $data['admin_id'] = $this->input->get('admin_id');
        $data['order_status'] = $this->input->get('order_status');

        $data['list'] = $this->Mscheduled_orders->get_order_lists($where, ($page-1)*$pageconfig['per_page'], $pageconfig['per_page']);
        $data_count = $this->Mscheduled_orders->get_order_count($where);
        $data['data_count'] = $data_count;
        $data['page'] = $page;

        //获取分页
        $pageconfig['base_url'] = "/scheduledorders";
        $pageconfig['total_rows'] = $data_count;
        $this->pagination->initialize($pageconfig);
        $data['pagestr'] = $this->pagination->create_links(); // 分页信息

        $data['status_text'] = C('scheduledorder.order_status.text'); //订单状态

        $data['admins'] = $this->Madmins->get_lists('id, fullname', array('is_del' => 1));

        $this->load->view("scheduledorders/index", $data);
    }


    /**
     * 选择预定订单类型
     */
    public function order_type() {
        $data = $this->data;
        $this->load->view('scheduledorders/order_type', $data);
    }


    /**
     * 新建预定订单
     */
    public function add($order_type) {
        $data = $this->data;
        if (IS_POST) {
            $post_data = $this->input->post();

            //判断这个客户是否已锁定点位
            $where['is_del'] = 0;
            $where['customer_id'] = $post_data['customer_id'];
            $where['order_type'] = $order_type;
            $where['order_status'] = C('scheduledorder.order_status.code.in_lock');
            $count = $this->Mscheduled_orders->count($where);
            if ($count > 0) {
                $this->success("该客户已存在锁定中的".$data['order_type_text'][$order_type]."订单！", '/scheduledorders/add/'.$order_type);
                exit;
            }

            //判断该客户是否存在正在锁定日期范围内的已释放的订单
            $where['order_status'] = C('scheduledorder.order_status.code.done_release');
            $where['lock_end_time>'] = date('Y-m-d');
            $orderinfo = $this->Mscheduled_orders->get_one('*', $where);
            if ($orderinfo) {
                $this->success("该客户上一次释放的订单还未到锁定结束日期，不能新建预定订单！", '/scheduledorders/add/'.$order_type);
                exit;
            }
            
            $post_data['create_user'] = $post_data['update_user'] = $data['userInfo']['id'];
            $post_data['create_time'] = $post_data['update_time'] = date('Y-m-d H:i:s');
            $id = $this->Mscheduled_orders->create($post_data);
            if ($id) {
                //下单成功更新点位相关锁定字段
                $update_data['lock_order_id'] = $id;
                $update_data['lock_customer_id'] = $post_data['customer_id'];
                $update_data['lock_start_time'] = $post_data['lock_start_time'];
                $update_data['lock_end_time'] = $post_data['lock_end_time'];
                $expire_time = strtotime($post_data['lock_end_time']." 23:59:59");
                $update_data['expire_time'] = $expire_time-86400;
                $update_data['is_lock'] = 1;
                $this->Mpoints->update_info($update_data, array('in' => array('id' => explode(',', $post_data['point_ids']))));

                $this->write_log($data['userInfo']['id'], 1, "新增".$data['order_type_text'][$post_data['order_type']]."预定订单,订单id【".$id."】");
                $this->success("添加成功！","/scheduledorders");
            } else {
                $this->success("添加失败！");
            }
        } else {
            $data['order_type'] = $order_type;

            //媒体列表
            $data['media_list'] = $this->Mmedias->get_lists("id, code, name, sort", array('type' => $order_type, 'is_del' => 0), array('sort' => 'asc'));
            $this->load->view("scheduledorders/add", $data);
        }
    }


    /**
     * 获取点位列表和数量
     */
    public function get_points() {
        $where['A.is_lock'] = 0;
        if($this->input->post('media_type')) $where['B.type'] = $this->input->post('media_type');
        if($this->input->post('media_id')) $where['A.media_id'] = $this->input->post('media_id');
        $points_lists = $this->Mpoints->get_points_lists($where);
        foreach ($points_lists as $key => $value) {
            switch ($value['point_status']) {
                case '1':
                    $class = 'badge-success';
                    break;
                case '3':
                    $class = 'badge-danger';
                    break;
            }
            $points_lists[$key]['point_status'] = '<span class="badge '.$class.'">'.C('public.points_status')[$value['point_status']].'</span>';
        }
        $this->return_json(array('flag' => true, 'points_lists' => $points_lists, 'count' => count($points_lists)));
    }


    /* 
     * 编辑订单
     */
    public function edit($id) {
        $data = $this->data;
        $data['info'] = $this->Mscheduled_orders->get_one("*", array('id' => $id));

        if ($data['info']['order_status'] == C('scheduledorder.order_status.code.done_release')) {
            $this->success('只有锁定中的订单才能够进行修改操作！', '/scheduledorders');
            exit;
        }

        if (IS_POST) {
            $post_data = $this->input->post();

            $post_data['update_user'] = $data['userInfo']['id'];
            $post_data['update_time'] = date('Y-m-d H:i:s');

            //先把之前所有已选择的点位的状态置为未锁定，再把重新选择的点位状态置为锁定
            $this->Mpoints->update_info(array('lock_order_id' => '', 'lock_customer_id' => '', 'lock_start_time' => '', 'lock_end_time' => '', 'expire_time' => '', 'is_lock' => 0), array('in' => array('id' => explode(',', $post_data['point_ids_old']))));
            
            $update_data['lock_order_id'] = $id;
            $update_data['lock_customer_id'] = $post_data['customer_id'];
            $update_data['lock_start_time'] = $post_data['lock_start_time'];
            $update_data['lock_end_time'] = $post_data['lock_end_time'];
            $expire_time = strtotime($post_data['lock_end_time']." 23:59:59");
            $update_data['expire_time'] = $expire_time-86400;
            $update_data['is_lock'] = 1;
            $this->Mpoints->update_info($update_data, array('in' => array('id' => explode(',', $post_data['point_ids']))));

            unset($post_data['point_ids_old']);
            $result = $this->Mscheduled_orders->update_info($post_data, array('id' => $id));
            if ($result) {
                $this->write_log($data['userInfo']['id'], 2, "编辑".$data['order_type_text'][$post_data['order_type']]."订单,订单id【".$id."】");
                $this->success("修改成功！","/scheduledorders");
            } else {
                $this->error("修改失败！请重试！");
            }
        } else {
            $data['customer'] = $this->Mcustomers->get_one('id, customer_name', array('id' => $data['info']['customer_id']));

            $data['order_type'] = $data['info']['order_type'];

            //媒体列表
            $data['media_list'] = $this->Mmedias->get_lists("id, code, name", array('type' => $data['order_type']));

            //已选择点位列表
            $where['in']['A.id'] = explode(',', $data['info']['point_ids']);
            $data['selected_points'] = $this->Mpoints->get_points_lists($where);
            
            $this->load->view("scheduledorders/add", $data);
        }
    }


    /**
     * 释放订单
     */
    public function release_points($id) {
        $info = $this->Mscheduled_orders->get_one("*", array('id' => $id));

        if ($info['order_status'] == C('scheduledorder.order_status.code.done_release')) {
            $this->error('解除锁定失败！请重试！', '/scheduledorders');
            exit;
        }

        if ($this->data['userInfo']['id'] != 1 && $info['create_user'] != $this->data['userInfo']['id']) {
            $this->error('您只能解除自己下的预定订单！', '/scheduledorders');
            exit;
        }

        $update_data['lock_order_id'] = $update_data['lock_customer_id'] = $update_data['lock_start_time'] = $update_data['lock_end_time'] = $update_data['expire_time'] = '';
        $update_data['is_lock'] = 0;
        $result = $this->Mpoints->update_info($update_data, array('in' => array('id' => explode(',', $info['point_ids']))));
        if (!$result) {
            $this->error('解除锁定失败！请重试！', '/scheduledorders');
        }

        //更新该订单的状态为“已释放”
        $this->Mscheduled_orders->update_info(array('order_status' => C('scheduledorder.order_status.code.done_release')), array('id' => $id));

        $this->success('解除锁定成功！已释放该订单的所有预定点位！', '/scheduledorders');
    }
    
    /**
     * 订单续期
     * @author yonghua
     */
    public function update_points($id) {
        $data = $this->data;
        $info = $this->Mscheduled_orders->get_one("order_status", ['id' => $id]);
        if($info['order_status'] != 2){
            $this->error('您只能续期即将到期的订单！', '/scheduledorders');
        }
        $time = date('Y-m-d');
        $end = date('Y-m-d', strtotime('+7 days'));
        $up =[
            'order_status' => 1,
            'lock_start_time' => $time,
            'lock_end_time' => $end,
            'update_time' => date('Y-m-d H:i:s'),
            'update_user' => $data['userInfo']['id']
        ];
        $res = $this->Mscheduled_orders->update_info($up, ['id' => $id]);
        if(!$res){
            $this->error('操作失败！', '/scheduledorders');
        }
        $this->success('续期成功！', '/scheduledorders');
    }


    /**
     * 预定订单详情   
     */
    public function detail($id) {
        $data = $this->data;
        $data['info'] = $this->Mscheduled_orders->get_one('*', array('id' => $id));

        //预定客户
        $data['info']['customer_name'] = $this->Mcustomers->get_one('customer_name', array('id' => $data['info']['customer_id']))['customer_name'];

        $data['status_text'] = C('scheduledorder.order_status.text'); //订单状态

        //预定点位列表
        $data['info']['selected_points'] = $this->Mpoints->get_points_lists(array('in' => array('A.id' => explode(',', $data['info']['point_ids']))));

        $this->load->view('scheduledorders/detail', $data);
    }


    /**
     * 导出预定点位列表
     */
    public function export($id, $type) {
        //加载phpexcel
        $this->load->library("PHPExcel");

        //设置表头
        if ($type == 1) {
            $table_header =  array(
                '点位编号'=>"points_code",
                '站台名称'=>"media_name",
                '规格'=>"spec",
            );
        } else {
            $table_header =  array(
                '点位编号'=>"points_code",
                '高杆名称'=>"media_name",
                '规格'=>"spec",
            );
        }

        $i = 0;
        foreach($table_header as  $k=>$v){
            $cell = PHPExcel_Cell::stringFromColumnIndex($i).'1';
            $this->phpexcel->setActiveSheetIndex(0)->setCellValue($cell, $k);
            $i++;
        }

        $scheduledorder = $this->Mscheduled_orders->get_one('*', array('id' => $id));

        $where['in']['A.id'] = explode(',', $scheduledorder['point_ids']);

        $customers = $this->Mcustomers->get_one("customer_name", array('id' => $scheduledorder['customer_id'], 'is_del' => 0)); //客户

        $list = $this->Mpoints->lists($where);

        $h = 2;
        foreach($list as $key=>$val){
            $j = 0;
            foreach($table_header as $k => $v){
                $cell = PHPExcel_Cell::stringFromColumnIndex($j++).$h;

                switch ($v) {
                    case 'spec':
                        $value = $type == 1 ? $val['size'].'（'.$val['spec_name'].'）' : $val['size'];
                        break;
                    case 'media_name':
                        $value = $val['media_name'].'（'.$val['media_code'].'）';
                        break;
                    default:
                        $value = $val[$v];
                        break;
                }
                
                $this->phpexcel->getActiveSheet(0)->setCellValue($cell, $value);
            }
            $h++;
        }

        $this->phpexcel->setActiveSheetIndex(0);
        // 输出
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename=预定点位表（客户：'.$customers['customer_name'].'）.xls');
        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel5');
        $objWriter->save('php://output');
    }
}

