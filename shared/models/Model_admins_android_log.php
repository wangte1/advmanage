<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Model_admins_android_log extends MY_Model {

    private $_table = 't_app_actions';

    public function __construct() {
        parent::__construct($this->_table);
    }
    
}