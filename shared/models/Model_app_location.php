<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Model_app_location extends MY_Model {

    private $_table = 't_app_location';

    public function __construct() {
        parent::__construct($this->_table);
    }

}