<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Model_orders extends MY_Model {

    private $_table = 't_orders';

    public function __construct() {
        parent::__construct($this->_table);
    }

    /**
     * 获取订单列表
     */
    public function get_order_lists($where = array(), $offset = 0, $pagesize = 0) {
    	$this->db->select('A.*, B.customer_name, C.name AS sales_name, C.sex AS sales_sex, C.phone_number AS sales_mobile');
		$this->db->from('t_orders A');
        $this->db->join('t_customers B', 'A.customer_id = B.id');
		$this->db->join('t_salesman C', 'A.sales_id = C.id');
		$this->db->where(array('A.is_del' => 0));
        if ($where) {
            $this->db->where($where);
        }
        //分页
        if($pagesize){
            $this->db->limit($pagesize, $offset);
        }

		$this->db->order_by('A.create_time', 'desc');

    	$result = $this->db->get();

        return $result->result_array();
    }


    /**
     * 获取订单数量
     */
    public function get_order_count($where = array()) {
        $this->db->from('t_orders A');
		$this->db->join('t_customers B', 'A.customer_id = B.id');
        $this->db->join('t_salesman C', 'A.sales_id = C.id');
        
        if($where){
            $this->db->where($where);
        }
        return $this->db->count_all_results();
    }


    /*
     * @param $d 查询条件
     * 1034487709@qq.com
     */
    public function statistics_orders($d = "",$order_type="",$sales=""){
        $fields= array('count(id) as num','sum(total_price) as total_price',"date_format(create_time,'%m') as month");
        if(empty($d)){
            $d = date("Y");
        }
        if($order_type){
            $where['order_type'] = $order_type;
        }
        if($sales){
            $where['sales_id'] = $sales;

        }

        $where['is_del'] = 0;
        $where["date_format(create_time,'%Y')"] = $d;
        return $this->get_lists($fields,$where,array("create_time"=>"asc"),0,0,array("date_format(create_time,'%m')"));
    }

    /*
     * 获取一个星期内即将到期的数量
     * 1034487709@qq.com
     */
    public function expire_time_orders(){
        $where['is_del'] = 0;
        $where['release_end_time>='] = date("Y-m-d");
        $where['release_end_time<='] =  date("Y-m-d",strtotime("+7 day"));
        $where['order_status'] =  C('order.order_status.code.in_put');
        return  $this->count($where);
    }


    /*
     * 获取已到期未下画订单的数量
     * 1034487709@qq.com
     */
    public function overdue_time_orders(){
        $where['is_del'] = 0;
        $where['release_end_time<'] = date("Y-m-d");
        $where['order_status'] =  C('order.order_status.code.in_put');
        return  $this->count($where);
    }
    
}