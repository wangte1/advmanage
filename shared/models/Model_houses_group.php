<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Model_houses_group extends MY_Model {

    private $_table = 't_houses_group';

    public function __construct() {
        parent::__construct($this->_table);
    }

}