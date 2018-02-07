<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Model_order_inspect_images_log extends MY_Model {

    private $_table = 't_order_inspect_images_log';

    public function __construct() {
        parent::__construct($this->_table);
    }

    /*
     * 获取验收图片日志列表
     */
    public function get_inspect_img ($where = array()) {
    	$this->db->select('A.*, B.name AS media_name, B.code AS media_code, B.id AS media_id');
        $this->db->from('t_order_inspect_images_log A');
        $this->db->join('t_medias B', 'A.media_id = B.id', 'right');

        if ($where) {
            $this->db->where($where);
        }

        $this->db->order_by('B.id', 'asc');
        
        $result = $this->db->get();

        return $result->result_array();
    }

}