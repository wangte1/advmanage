<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Model_houses_points_format extends MY_Model {

    private $_table = 't_houses_points_format';

    public function __construct() {
        parent::__construct($this->_table);
    }

}