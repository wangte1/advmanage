<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Model_inspect_images extends MY_Model {

    private $_table = 't_inspect_images';

    public function __construct() {
        parent::__construct($this->_table);
    }

    public function get_lists_img($order_id){
        $this->db->select('A.*, B.name,B.code,B.id');
        $this->db->from('t_inspect_images A');
        $this->db->join('t_medias B', 'A.media_id = B.id');
        $this->db->where(array('A.order_id' => $order_id));
        $result = $this->db->get();

        return $result->result_array();
    }

}