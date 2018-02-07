<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Model_houses_want_orders extends MY_Model {

    private $_table = 't_houses_want_orders';

    public function __construct() {
        parent::__construct($this->_table);
    }
    
}