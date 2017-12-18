<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 补贴信息表
 * @author chaokai@gz-zc.cn
 */
class Model_allowance_place extends MY_Model{
    
    private $_table = 't_allowance_place';
    public function __construct(){
        
        parent::__construct($this->_table);
    }
}
