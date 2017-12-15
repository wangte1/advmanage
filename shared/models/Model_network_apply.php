<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Model_network_apply extends MY_Model {

    private $_table = 't_network_apply';

    public function __construct() {
        parent::__construct($this->_table);
    }
    
}