<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Model_houses_tour_points extends MY_Model {

    private $_table = 't_houses_tour_points';

    public function __construct() {
        parent::__construct($this->_table);
    }

}