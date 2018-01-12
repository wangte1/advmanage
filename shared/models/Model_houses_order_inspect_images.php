<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Model_houses_order_inspect_images extends MY_Model {

    private $_table = 't_houses_order_inspect_images';

    public function __construct() {
        parent::__construct($this->_table);
    }

    /*
     * 获取验收图片列表
     */
    public function get_inspect_img ($where = array()) {
    	$this->db->select('A.*, B.code AS point_code, B.ban, B.unit, B.floor, B.addr, C.name AS houses_name, D.name AS houses_area_name');
        $this->db->from('t_houses_order_inspect_images A');
        $this->db->join('t_houses_points B', 'A.point_id = B.id');
        $this->db->join('t_houses C', 'B.houses_id = C.id');
        $this->db->join('t_houses_area D', 'B.area_id = D.id');

        if ($where) {
            $this->db->where($where);
        }

        $result = $this->db->get();

        return $result->result_array();
    }
}