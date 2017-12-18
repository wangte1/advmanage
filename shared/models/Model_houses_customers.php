<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Model_houses_customers extends MY_Model {

    private $_table = 't_houses_customers';

    public function __construct() {
        parent::__construct($this->_table);
    }

}