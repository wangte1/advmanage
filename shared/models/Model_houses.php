<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Model_houses extends MY_Model {

    private $_table = 't_houses';

    public function __construct() {
        parent::__construct($this->_table);
    }
    
    /**
     * @author: 1034487709@qq.com
     * @description 获取列表
     * @param: array fields 查询的字段
     * @param: array where 查询条件
     * @param: array join 多表查询
     * @param: string  order_by  排序
     * @param: string limit 限制查多少条
     * @param: string  group_by  分组
     * @return: array
     */
    public function get_lists($fields = array(), $where = array(), $order_by = array(), $pagesize = 0,$offset = 0,  $group_by = array()) {
        if(!empty($fields)) {
            if(is_array($fields)) {
                $fields = implode(',', $fields);
            }
        } else {
            $fields = '*';
        }
        $this->db->from($this->_table);
        if($fields) {
            $this->db->select($fields);
        }
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

}