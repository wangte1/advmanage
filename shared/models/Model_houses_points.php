<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Model_houses_points extends MY_Model {

    private $_table = 't_houses_points';

    public function __construct() {
        parent::__construct($this->_table);
    }
    
    
    /*
     * 获取投放点位列表
     */
    public function get_points_lists($where = array()){
        $this->db->select('A.id, A.code, A.price, C.name as houses_area_name, A.addr, A.point_status, B.name AS houses_name');
    	$this->db->from('t_houses_points A');
    	$this->db->join('t_houses B', 'A.houses_id = B.id');
    	$this->db->join('t_houses_area C', 'A.houses_id = C.id');
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
    
    	//$this->db->order_by('B.sort', 'asc');
    	$this->db->order_by('A.id', 'asc');
    
    	$result = $this->db->get();
    
    	return $result->result_array();
    }

}