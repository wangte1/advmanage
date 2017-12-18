<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Model_make_company extends MY_Model {

    private $_table = 't_make_company';

    public function __construct() {
        parent::__construct($this->_table);
    }
    
}