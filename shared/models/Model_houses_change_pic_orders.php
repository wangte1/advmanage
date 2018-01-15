<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Model_houses_change_pic_orders extends MY_Model {

    private $_table = 't_houses_change_pic_orders';

    public function __construct() {
        parent::__construct($this->_table);
    }

    /**
     * 获取订单列表
     */
    public function get_order_lists($where = array(), $offset = 0, $pagesize = 0) {
    	$this->db->select('A.*, B.order_code, B.order_type, B.release_start_time, B.release_end_time, B.total_price, C.name AS customer_name, D.name AS sales_name, D.sex AS sales_sex, D.phone_number AS sales_mobile');
		$this->db->from('t_houses_change_pic_orders A');
        $this->db->join('t_houses_orders B', 'A.order_code = B.order_code');
        $this->db->join('t_houses_customers C', 'B.customer_id = C.id');
		$this->db->join('t_salesman D', 'B.sales_id = D.id');
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
        $this->db->from('t_houses_change_pic_orders A');
        $this->db->join('t_orders B', 'A.order_code = B.order_code', 'left');
        $this->db->join('t_customers C', 'B.customer_id = C.id', 'left');
        
        if($where){
            $this->db->where($where);
        }
        return $this->db->count_all_results();
    }
    
}