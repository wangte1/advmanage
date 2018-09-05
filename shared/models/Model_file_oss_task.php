<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Model_file_oss_task extends MY_Model {

    private $_table = 't_file_oss_task';

    public function __construct() {
        parent::__construct($this->_table);
    }
    
}