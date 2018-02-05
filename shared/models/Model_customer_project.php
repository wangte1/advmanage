<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Model_customer_project extends MY_Model {

    private $_table = 't_customer_project';

    public function __construct() {
        parent::__construct($this->_table);
    }
    
}