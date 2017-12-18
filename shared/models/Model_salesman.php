<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Model_salesman extends MY_Model {

    private $_table = 't_salesman';

    public function __construct() {
        parent::__construct($this->_table);
    }

}