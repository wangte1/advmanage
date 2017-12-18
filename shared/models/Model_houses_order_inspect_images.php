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
    	$this->db->select('A.*, B.name AS houses_name');
        $this->db->from('t_houses_order_inspect_images A');
        $this->db->join('t_houses B', 'A.houses_id = B.id', 'right');

        if ($where) {
            $this->db->where($where);
        }

        $result = $this->db->get();

        return $result->result_array();
    }
}