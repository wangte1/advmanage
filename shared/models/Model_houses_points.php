<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Model_houses_points extends MY_Model {

    private $_table = 't_houses_points';

    public function __construct() {
        parent::__construct($this->_table);
        $this->load->model([
            'Model_houses_orders' => 'Mhouses_orders'
        ]);
    }
    
    /*
     * 获取投放点位列表
     */
    public function get_points_lists($where = array(), $order_by = array('A.houses_id' => 'desc', 'A.area_id' => 'desc', 'A.ban' => 'desc'), $pagesize = 0,$offset = 0,  $group_by = array()){
    	
    	//分组时候的统计
    	$tmp_count = '';
    	if(count($group_by) > 0) {
			$tmp_count = 'count(0) as count,';
		}
    	
        $this->db->select($tmp_count.'A.*, C.name as houses_area_name, A.addr, A.point_status, B.province, B.city, B.area,B.name AS houses_name, B.put_trade,B.grade,B.is_check_out,C.grade AS area_grade,D.size');
    	$this->db->from('t_houses_points A', 'left');
    	$this->db->join('t_houses B', 'A.houses_id = B.id', 'left');
    	$this->db->join('t_houses_area C', 'A.area_id = C.id', 'left');
    	$this->db->join('t_houses_points_format D', 'A.type_id = D.type', 'left');
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
    
    /*
     * 获取投放点位列表,优化
     */
    public function get_points_lists_optimize($where = array(), $order_by = array(), $pagesize = 0,$offset = 0,  $group_by = array()){
        
        //分组时候的统计
        $tmp_count = '';
        if(count($group_by) > 0) {
            $tmp_count = 'count(0) as count,';
        }
        
        $this->db->select($tmp_count.'A.*, C.name as houses_area_name, A.addr, A.point_status, B.province, B.city, B.area,B.name AS houses_name, B.put_trade,B.grade,B.is_check_out,C.grade AS area_grade,D.size');
        $this->db->from('t_houses_points A');
        $this->db->join('t_houses B', 'A.houses_id = B.id');
        $this->db->join('t_houses_area C', 'A.area_id = C.id');
        $this->db->join('t_houses_points_format D', 'A.type_id = D.type');
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
    
    /*
     * 获取投放点位数量
     */
    public function get_points_count($where = array()){
    	$this->db->select('count(0) as count');
    	$this->db->from('t_houses_points A', 'left');
    	$this->db->join('t_houses B', 'A.houses_id = B.id', 'left');
    	$this->db->join('t_houses_area C', 'A.area_id = C.id', 'left');
    	$this->db->join('t_houses_points_format D', 'A.type_id = D.type', 'left');
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
    
    /**
     * @author: 1034487709@qq.com
     * @description 获取所有信息
     * @param: mixed $fields array('uid', '..')
     * @param: array $where array('name' => 'aaa', 'uid >' => $id);
     */
    public function get_count($where = array()){
    	
    	$this->db->select('A.id');
    	$this->db->from('t_houses_points A');
    	$this->db->join('t_houses B', 'A.houses_id = B.id');
    	$this->db->join('t_houses_area C', 'A.area_id = C.id');
    	$this->db->join('t_houses_points_format D', 'A.type_id = D.type');
    	$this->db->where(array('A.is_del' => 0, 'B.is_del' => 0));
    	
    	if(!empty($where)) {
    		if(isset($where['like'])) {
    			foreach($where['like'] as $k => $v) {
    				$this->db->like($k, $v);
    			}
    			unset($where['like']);
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
    		if($where){
    			$this->db->where($where);
    		}
    	}
    	return $this->db->count_all_results();
    }
    
    /**
     * 
     * @param unknown $where
     */
    public function get_points_by_code($where){
        $this->db->select('A.id, A.code, A.floor,A.unit,A.ban, A.price, C.name as houses_area_name, A.addr,A.ad_num, A.ad_use_num, A.point_status, B.name AS houses_name, D.size');
        $this->db->from('t_houses_points A');
        $this->db->join('t_houses B', 'A.houses_id = B.id', 'left');
        $this->db->join('t_houses_area C', 'A.area_id = C.id', 'left');
        $this->db->join('t_houses_points_format D', 'A.type_id = D.type', 'left');
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
        
        $result = $this->db->get();
        
        return $result->result_array();
    }
    
    /*
     * 获取投放点位列表
     */
    public function get_points_lists_page($where = array(), $order_by=array()){
        
        $this->db->select('A.id, A.code, A.price, C.name as houses_area_name, A.addr, A.point_status, B.name AS houses_name, D.size');
        $this->db->from('t_houses_points A');
        $this->db->join('t_houses B', 'A.houses_id = B.id', 'left');
        $this->db->join('t_houses_area C', 'A.area_id = C.id', 'left');
        $this->db->join('t_houses_points_format D', 'A.type_id = D.type', 'left');
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
        
        $result = $this->db->get();
        
        return $result->result_array();
    }
    
    public function get_usable_point($fields='*', $where=[], $startTime=''){
        
        $list = $this->get_lists($fields, $where);
        if($list){
            $order_ids = array_column($list, 'order_ids');
            if(count($order_ids) == 0) return $list;
            //获取点位对应的所有order_id是否满足新订单
            $order_ids = array_unique($order_ids);
            $order_list = $this->Mhouses_orders->get('id, release_end_time, is_del', ['in' => ['id' => $order_ids], 'release_end_time <' => $startTime]);
            if($order_list){
                $lists = $list;
                foreach ($list as $k => $v){
                    foreach ($order_list as $key => $val){
                        if($v['order_id'] && $v['order_id'] == $val['id']){
                            if(strtotime($val['release_end_time']) > strtotime($startTime)){
                                unset($lists[$k]);
                            }
                        }
                    }
                }
                return $lists;
            }
        }
        return [];
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
     * 去重查询楼盘等级
     */
    public function get_distinct_lists($where = array()){
    	
    	$this->db->distinct();
    	$this->db->select('houses_id,houses_type,floor_num');
    	$this->db->from('t_houses_points');
    	
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
    	//$query = $this->db->query("select distinct houses_id,houses_type from t_houses_points");
    	//return (array)$query->result();
    }
    

}
