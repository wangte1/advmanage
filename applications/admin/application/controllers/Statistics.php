<?php
/**
 * 统计管理控制器
 * 1034487709@qq.com
 */
defined('BASEPATH') or exit('No direct script access allowed');
class Statistics extends MY_Controller{

    public function __construct(){
        parent::__construct();
        $this->load->model([
            'Model_orders' => 'Morders',
            'Model_salesman' => 'Msalesman'
        ]);

        $this->data['code'] = 'statistics_manage';
        $this->data['active'] = 'statistics_order_list';
    }

    /*
    * 订单统计
    * 1034487709@qq.com
    */
    public function index(){
        $data = $this->data;
        $data['title'] = array("资源管理","点位管理");
        $y = $this->input->get("y")?$this->input->get("y"):date("Y");
        $order_type = $this->input->get("type");
        $sales = $this->input->get("sales");
        $data['sales'] = $sales;
        //获取当年的订单数
        $list = $this->Morders->statistics_orders($y,$order_type,$sales);

        $money = array();
        $orders = array();
        $total_money = 0;
        $total_orders = 0;
        for($i=1;$i<=12;$i++){
            foreach($list as $key=>$val){
                if(intval($val['month']) == $i){
                    $money[$i] = round(($val['total_price']/10000) ,3);
                    $orders[$i] = intval($val['num']);
                    $total_money += $money[$i];
                    $total_orders += $orders[$i];
                    break;
                }else{
                    $money[$i] = 0;
                    $orders[$i] = 0;
                }
            }
        }
       $data['money_data'] =  json_encode(array_values($money));
       $data['orders_data'] =  json_encode(array_values($orders));
       $data['y'] = $y;
       $data['order_type'] = $order_type;
       $data['total_orders'] = $total_orders;
       $data['total_money'] = $total_money;

       $data['years'] = C("public.years") ;
       $data['media_type'] = C("public.media_type") ;

       //获取所有业务员
       $data['salesman'] = $this->Msalesman->get_lists("id,name",array("is_del"=>0));


        $this->load->view("statistics/index",$data);
    }


}