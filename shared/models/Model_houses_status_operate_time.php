<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Model_houses_status_operate_time extends MY_Model {

    private $_table = 't_houses_status_operate_time';

    public function __construct() {
        parent::__construct($this->_table);
    }


}