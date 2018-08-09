<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Model_houses_customers_linkman_log extends MY_Model {

    private $_table = 't_houses_customers_linkman_log';

    public function __construct() {
        parent::__construct($this->_table);
    }

}