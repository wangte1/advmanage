<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Model_houses_work_order_detail extends MY_Model {

    private $_table = 't_houses_work_order_detail';

    public function __construct() {
        parent::__construct($this->_table);
    }
    public function get_points_lists($where = array(), $order_by = array(), $pagesize = 0, $offset = 0,  $group_by = array()){
        $this->db->select('A.id,A.status,A.no_img,A.pano_img,B.code,B.houses_name,B.area_name,B.ban,B.unit,B.floor,B.addr');
        $this->db->from('t_houses_work_order_detail A', 'left');
        $this->db->join('t_houses_points B', 'A.point_id = B.id', 'left');
        $this->db->where(array('B.is_del' => 0));
        
        if(isset($where['like'])) {
            foreach($where['like'] as $k => $v) {
                $this->db->like($k, $v);
            }
            unset($where['like']);
        }
        //字段间的比较
        if(isset($where['field'])){
            foreach ($where['field'] as $key => $value){
                $this->db->where("{$key}", $value, FALSE);
            }
            unset($where['field']);
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
}