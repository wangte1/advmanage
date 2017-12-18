<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Model_change_points_record extends MY_Model {

    private $_table = 't_change_points_record';

    public function __construct() {
        parent::__construct($this->_table);
    }

    public function get_one_change($id) {
    	$sql = "SELECT * FROM t_change_points_record WHERE order_id = $id AND (remove_points = '' OR add_points = '')";
    	$result = $this->db->query($sql);
    	$data = $result->result_array();
    	if(!isset($data[0])) {
            return $data;
        }
        return $data[0];
    }

}