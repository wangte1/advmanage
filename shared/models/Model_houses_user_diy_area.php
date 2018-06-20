<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Model_houses_user_diy_area extends MY_Model {

    private $_table = 't_houses_user_diy_area';

    public function __construct() {
        parent::__construct($this->_table);
    }
    
}