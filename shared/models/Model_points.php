<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Model_points extends MY_Model {

    private $_table = 't_points';

    public function __construct() {
        parent::__construct($this->_table);
    }

    /*
     * 点位列表
     */
    public function lists($where = array(), $offset = 0, $pagesize = 0) {
        $this->db->select('A.*, B.code AS media_code, B.new_code, B.name AS media_name, B.type AS media_type, C.name as spec_name, C.size, D.customer_name, E.release_start_time, E.release_end_time, E.project_id');
        $this->db->from('t_points A');
        $this->db->join('t_medias B', 'A.media_id = B.id', 'left');
        $this->db->join('t_specifications C', 'A.specification_id = C.id','left');
        $this->db->join('t_customers D', 'A.customer_id = D.id', 'left');
        $this->db->join('t_orders E', 'A.order_id = E.id', 'left');
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

        //分页
        if($pagesize){
            $this->db->limit($pagesize, $offset);
        }

        $this->db->order_by('B.sort', 'asc');
        $this->db->order_by('A.id', 'asc');

        $result = $this->db->get();

        return $result->result_array();
    }

    /**
     * 获取点位数量
     */
    public function get_point_count($where = array()) {
        $this->db->from('t_points A');
        $this->db->join('t_medias B', 'A.media_id = B.id', 'left');
        $this->db->join('t_specifications C', 'A.specification_id = C.id', 'left');
        $this->db->join('t_customers D', 'A.customer_id = D.id', 'left');
        $this->db->join('t_orders E', 'A.order_id = E.id', 'left');
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
        
        return $this->db->count_all_results();
    }


    /* 
     * 获取投放点位列表
     */
    public function get_points_lists($where = array()){
    	$this->db->select('A.id, A.points_code, A.price, A.address, A.point_status, B.code AS media_code, B.name AS media_name, C.name as specification_name, C.size');
		$this->db->from('t_points A');
        $this->db->join('t_medias B', 'A.media_id = B.id');
		$this->db->join('t_specifications C', 'A.specification_id = C.id');
		$this->db->where(array('A.is_del' => 0, 'B.is_del' => 0));

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

        $this->db->order_by('B.sort', 'asc');
		$this->db->order_by('A.id', 'asc');

    	$result = $this->db->get();

        return $result->result_array();
    }


    /* 
     * 获取制作数量
     */
    public function get_make_info($where = array()){
        $this->db->select('B.id AS spec_id, B.name AS spec_name, B.size, count(A.id) AS counts, count(distinct(D.code)) AS high_count, sum(C.make_num) AS make_num');
        $this->db->from('t_points A');
        $this->db->join('t_specifications B', 'A.specification_id = B.id');
        $this->db->join('t_points_make_num C', 'A.id = C.point_id');
        $this->db->join('t_medias D', 'A.media_id = D.id');
        $this->db->where(array('A.is_del' => 0, 'B.is_del' => 0));
        
        if(isset($where['in'])) {
            foreach($where['in'] as $k => $v) {
                $this->db->where_in($k, $v);
            }
            unset($where['in']);
        }
        
        if($where){
            $this->db->where($where);
        }

        $this->db->order_by('A.create_time', 'desc');

        $this->db->group_by(array('B.size'));

        $result = $this->db->get();

        return $result->result_array();
    }


    /* 
     * 获取高杆制作数量
     */
    public function get_make_high($where = array()){
        $this->db->select('A.code AS media_code, A.name AS media_name');
        $this->db->from('t_medias A');
        $this->db->join('t_points B', 'A.id = B.media_id');
        $this->db->where(array('A.is_del' => 0, 'B.is_del' => 0));

        if(isset($where['in'])) {
            foreach($where['in'] as $k => $v) {
                $this->db->where_in($k, $v);
            }
            unset($where['in']);
        }
        
        if($where){
            $this->db->where($where);
        }

        $this->db->order_by('A.create_time', 'desc');
        $this->db->group_by(array('media_name'));
        
        $result = $this->db->get();

        return $result->result_array();
    }


    /* 
     * 确认函点位列表
     */
    public function get_confirm_points($where = array(), $order_by = array(), $group_by = array()){
        $this->db->select('A.id AS media_id, A.code AS media_code, A.name AS media_name, count(B.id) AS counts, C.name as specification_name, C.size');
        $this->db->from('t_medias A');
        $this->db->join('t_points B', 'A.id = B.media_id');
        $this->db->join('t_specifications C', 'B.specification_id = C.id');
        $this->db->where(array('A.is_del' => 0, 'B.is_del' => 0, 'C.is_del' => 0));

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

        $result = $this->db->get();

        return $result->result_array();
    }

}