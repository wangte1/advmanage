<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Model_points_make_num extends MY_Model {

    private $_table = 't_points_make_num';

    public function __construct() {
        parent::__construct($this->_table);
    }

}