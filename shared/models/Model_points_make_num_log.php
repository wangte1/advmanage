<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Model_points_make_num_log extends MY_Model {

    private $_table = 't_points_make_num_log';

    public function __construct() {
        parent::__construct($this->_table);
    }

    /* 
     * 获取制作数量
     */
    public function get_make_info($where = array()){
        $this->db->select('B.id AS spec_id, B.name AS spec_name, B.size, count(A.id) AS counts, count(distinct(D.code)) AS high_count, sum(C.make_num) AS make_num');
        $this->db->from('t_points A');
        $this->db->join('t_specifications B', 'A.specification_id = B.id');
        $this->db->join('t_points_make_num_log C', 'A.id = C.point_id');
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

}