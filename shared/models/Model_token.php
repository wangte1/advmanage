<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Model_token extends MY_Model {

    private $_table = 't_token';

    public function __construct() {
        parent::__construct($this->_table);
    }
    
}