<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Model_houses_assign extends MY_Model {

    private $_table = 't_houses_assign';

    public function __construct() {
        parent::__construct($this->_table);
    }
    
    //获取连接查询信息
    public function get_join_lists($where = array(), $order_by = array(), $pagesize = 0,$offset = 0,  $group_by = array()) {
        $this->db->select('A.*, B.province, B.city, B.area, B.name as houses_name, C.order_type,C.release_start_time,C.release_end_time,C.customer_id, D.name as customer_name,E.fullname as charge_name');
        $this->db->from('t_houses_assign A');
        $this->db->join('t_houses B', 'A.houses_id = B.id');
        $this->db->join('t_houses_orders C', 'A.order_id = C.id');
        $this->db->join('t_houses_customers D', 'C.customer_id = D.id');
        $this->db->join('t_admins E', 'A.charge_user = E.id');
        $this->db->where(array('A.is_del' => 0));
        
        if(isset($where['like'])) {
            foreach($where['like'] as $k => $v) {
                $this->db->like($k, $v);
            }
            unset($where['like']);
        }
        
        if(isset($where['or_like'])) {
            foreach($where['or_like'] as $k => $v) {
                $this->db->or_like($k, $v);
            }
            unset($where['or_like']);
        }
        
        if(isset($where['in'])) {
            foreach($where['in'] as $k => $v) {
                $this->db->where_in($k, $v);
            }
            unset($where['in']);
        }
        if(isset($where['not_in'])) {
            foreach($where['not_in'] as $k => $v) {
                $this->db->where_not_in($k, $v);
            }
            unset($where['not_in']);
        }
      
        if(isset($where['or'])) {
            $this->db->group_start();
            foreach($where['or'] as $k => $v) {
                $this->db->or_where($k, $v);
            }
            unset($where['or']);
            $this->db->group_end();
        }
        
        if($where){
            $this->db->where($where);
        }
        
        if($order_by) {
            foreach($order_by as $k => $v) {
                $this->db->order_by($k, $v);
            }
        }
        if($group_by) {
            $this->db->group_by($group_by);
        }
        if($pagesize > 0) {
            $this->db->limit($pagesize, $offset);
        }
        $result = $this->db->get();
        return $result->result_array();
    }
    
    //获取连接个数
    public function join_count($where = array()) {
    	$this->db->select('count(0) as count');
        $this->db->from('t_houses_assign A');
        $this->db->join('t_houses B', 'A.houses_id = B.id');
        $this->db->join('t_houses_orders C', 'A.order_id = C.id');
        $this->db->join('t_houses_customers D', 'C.customer_id = D.id');
        $this->db->join('t_admins E', 'A.charge_user = E.id');
        $this->db->where(array('A.is_del' => 0));
        
        if(isset($where['like'])) {
            foreach($where['like'] as $k => $v) {
                $this->db->like($k, $v);
            }
            unset($where['like']);
        }
        
        if(isset($where['or_like'])) {
            foreach($where['or_like'] as $k => $v) {
                $this->db->or_like($k, $v);
            }
            unset($where['or_like']);
        }
        
        if(isset($where['in'])) {
            foreach($where['in'] as $k => $v) {
                $this->db->where_in($k, $v);
            }
            unset($where['in']);
        }
        if(isset($where['not_in'])) {
            foreach($where['not_in'] as $k => $v) {
                $this->db->where_not_in($k, $v);
            }
            unset($where['not_in']);
        }
      
        if(isset($where['or'])) {
            $this->db->group_start();
            foreach($where['or'] as $k => $v) {
                $this->db->or_where($k, $v);
            }
            unset($where['or']);
            $this->db->group_end();
        }
        
        if($where){
            $this->db->where($where);
        }
        
       
        $result = $this->db->get();
        return $result->result_array();
    }
}