<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config = array(
    'upload_dir' => '../../uploads/',

    'portraint' => array(
            'upload_path'   => '../../uploads/portrait/',
            'allowed_types' => 'jpg|png',
            'max_size'      => 1024*5,
            'max_width'     => 2000,
            'max_height'    => 2000,
            'encrypt_name'  => TRUE,
            'remove_spaces' => TRUE,
            'use_time_dir'  => TRUE,      //是否按上传时间分目录存放
            'time_method_by_day'=> FALSE, //分目录存放的方式：按天 或 按月  默认按月
    )
);
