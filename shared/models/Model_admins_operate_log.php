<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Model_admins_operate_log extends MY_Model {

    private $_table = 't_admins_operate_log';

    public function __construct() {
        parent::__construct($this->_table);
    }
    
}