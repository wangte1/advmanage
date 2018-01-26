<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 *CI框架分页类配置文件
 */
$config = array(

    //1034487709@qq.com
     'page_lists' => array(
            'first_link' => '首页',
            'last_link' => '尾页',
            'prev_link' => '<',
            'next_link' => '>',
            'use_page_numbers' => TRUE,
            'prev_tag_open' => '<li class="prev">',
            'prev_tag_close' => '</li>',
            'next_tag_open' => '<li class="next">',
            'next_tag_close' => '</li>',
            'first_tag_open' => '<li>',
            'first_tag_close' => '</li>',
            'last_tag_open' => '<li>',
            'last_tag_close' => '</li>',
            'num_tag_open' => '<li>',
            'num_tag_close' => '</li>',
            'cur_tag_open' => ' <li class="active"><a >', //当前也标签
            'cur_tag_close' => '</a></li>',
            'page_query_string' => TRUE,
            'reuse_query_string' =>TRUE,
            'attributes' => array('rel'=>FALSE),
            'per_page' =>10// 每页条目数

    ),
);
