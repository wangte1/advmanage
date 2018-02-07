<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Model_scheduled_orders extends MY_Model {

    private $_table = 't_scheduled_orders';

    public function __construct() {
        parent::__construct($this->_table);
    }

    /**
     * 获取预定订单列表
     */
    public function get_order_lists($where = array(), $offset = 0, $pagesize = 0) {
        $this->db->select('A.*, B.customer_name, C.fullname AS admin_name');
        $this->db->from('t_scheduled_orders A');
        $this->db->join('t_customers B', 'A.customer_id = B.id');
        $this->db->join('t_admins C', 'A.create_user = C.id');
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
     * 获取预定订单数量
     */
    public function get_order_count($where = array()) {
        $this->db->from('t_scheduled_orders A');
        $this->db->join('t_customers B', 'A.customer_id = B.id');
        $this->db->join('t_admins C', 'A.create_user = C.id');
        $this->db->where(array('A.is_del' => 0));

        if($where){
            $this->db->where($where);
        }
        return $this->db->count_all_results();
    }

}