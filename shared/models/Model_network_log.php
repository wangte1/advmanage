<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Model_network_log extends MY_Model {

    private $_table = 't_network_log';

    public function __construct() {
        parent::__construct($this->_table);
    }
    
}