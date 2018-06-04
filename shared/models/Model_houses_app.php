<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Model_houses_app extends MY_Model {

    private $_table = 't_app_version';

    public function __construct() {
        parent::__construct($this->_table);
    }

}