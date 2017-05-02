<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Model_medias extends MY_Model {

    private $_table = 't_medias';

    public function __construct() {
        parent::__construct($this->_table);
    }

}