<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
use OSS\OssClient;
use OSS\Core\OssException;
/**
 * Cookie 加密
 */
if ( ! function_exists('encrypt')) {
	function encrypt($array = array()){
		$info = base64_encode(json_encode($array));
		$num = ceil(strlen($info)/1.5);
		$key1 = substr($info,0,$num);
		$result = strtr($info,array($key1=>strrev($key1)));
		$key2 = substr($result, -$num,$num-2);
		$result = strtr($result,array($key2=>strrev($key2)));
		return $result;
	}
}

/**
 * Cookie 解密
 */
if ( ! function_exists('decrypt')) {
	function decrypt($str = ''){
		$num = ceil(strlen($str)/1.5);
		$key2 = substr($str, -$num,$num-2);
		$str = strtr($str,array($key2=>strrev($key2)));
		$key1 = substr($str,0,$num);
		$result = strtr($str,array($key1=>strrev($key1)));
		$info = json_decode(base64_decode($result),true);
		return $info;
	}
}


/**
 *发送短信
 */
if ( ! function_exists('send_msg')) {
    function send_msg($tel, $msg = ''){
        if (empty($tel) || empty($msg)){
            return false;
        }
        $CI=&get_instance();
        $CI->load->library('sms', array(C("sms")));
        if (is_array($tel)){
            $tel = implode(',', $tel);
        }

        try {
            return $CI->sms->send_sms_huaxing($tel, $msg,'');
        }catch (Exception $e) {
            echo $e->getMessage(), "\n";
        }
    }

}


/**
 * 获取随机数
 */
if (! function_exists('get_code')){
    function get_code(){
        return  rand(100000, 999999);
    }
}


/**
 * 更复杂的获取随机数
 */
if (! function_exists('get_complex_code')){
    function  get_complex_code($length = 6){
        $str = '';
        $pa = '1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLOMNOPQRSTUVWXYZ';
        for($i=0; $i<$length; $i++){
             $str .= $pa{mt_rand(0,35)};
        }
        return $str;
     }
}


/**
 * 图片上传
 * @param string $file_name
 */
if (! function_exists('upload_file')){
    function upload_file($file_name, $config=''){
        $return_msg = array();
        $CI=&get_instance();
        $CI->load->library('upload', $config);
        if ( ! $CI->upload->do_upload($file_name)){
            $return_msg['flag'] = FALSE;
            $return_msg['data'] = $CI->upload->display_errors();
        }else{
            $return_msg['flag'] = TRUE;
            $return_msg['data'] = $CI->upload->data();
        }
        return $return_msg;
    }
}


/**
 * 获取加密用户密码
 *
 * @param string $file_name
 */
if (! function_exists('get_encode_pwd')){
    function get_encode_pwd($password){
        if (empty($password)){
            return FALSE;
        }
        $password = md5(strtolower($password));
        return $password;
    }
}


/**
 * 将二维数组中的第一维转换为和某个第二维字段值关联
 */
if (! function_exists('change_arr_key_by_somekey')){
    function change_arr_key_by_somekey($arr = array(), $somekey){
        $arr_somekey = array();
        if ($arr){
            foreach ($arr as $key=>$v){
                $arr_somekey[$v[$somekey]] = $v;
            }
        }
        return $arr_somekey;
    }


}



/**
 * 系统加密方法
 * @param string $data 要加密的字符串
 * @param string $key  加密密钥
 * @param int $expire  过期时间 单位 秒
 * @return string
 */
if (! function_exists('think_encrypt')){
    function think_encrypt($data, $key = '', $expire = 0) {
        $key  = md5(empty($key) ? C('DATA_AUTH_KEY') : $key);
        $data = base64_encode($data);
        $x    = 0;
        $len  = strlen($data);
        $l    = strlen($key);
        $char = '';
        for ($i = 0; $i < $len; $i++) {
            if ($x == $l) $x = 0;
            $char .= substr($key, $x, 1);
            $x++;
        }
        $str = sprintf('%010d', $expire ? $expire + time():0);
        for ($i = 0; $i < $len; $i++) {
            $str .= chr(ord(substr($data, $i, 1)) + (ord(substr($char, $i, 1)))%256);
        }
        return str_replace(array('+','/','='),array('-','_',''),base64_encode($str));
    }
}

/**
 * 系统解密方法
 * @param  string $data 要解密的字符串 （必须是think_encrypt方法加密的字符串）
 * @param  string $key  加密密钥
 * @return string
 */
if (! function_exists('think_decrypt')){
    function think_decrypt($data, $key = ''){
        $key    = md5(empty($key) ? C('DATA_AUTH_KEY') : $key);
        $data   = str_replace(array('-','_'),array('+','/'),$data);
        $mod4   = strlen($data) % 4;
        if ($mod4) {
            $data .= substr('====', $mod4);
        }
        $data   = base64_decode($data);
        $expire = substr($data,0,10);
        $data   = substr($data,10);

        if($expire > 0 && $expire < time()) {
            return '';
        }
        $x      = 0;
        $len    = strlen($data);
        $l      = strlen($key);
        $char   = $str = '';

        for ($i = 0; $i < $len; $i++) {
            if ($x == $l) $x = 0;
            $char .= substr($key, $x, 1);
            $x++;
        }

        for ($i = 0; $i < $len; $i++) {
            if (ord(substr($data, $i, 1))<ord(substr($char, $i, 1))) {
                $str .= chr((ord(substr($data, $i, 1)) + 256) - ord(substr($char, $i, 1)));
            }else{
                $str .= chr(ord(substr($data, $i, 1)) - ord(substr($char, $i, 1)));
            }
        }
        return base64_decode($str);
    }

}

/**
 * 获取图片地址全路径
 *
 * @param string $img_uri 数据库存放的图片资源文件名
 */
if (! function_exists('get_full_img_url')){
    function get_full_img_url($img_uri, $is_full = TRUE, $sub_dir = ''){
        if (strpos($img_uri, "http://") !== false){
            return $img_uri;
        }

        $img_url = '';
        $is_uploads_dir = strpos($img_uri, '/uploads/');
        if ($is_uploads_dir !== false){
            if ($is_full){
                $img_url = C('domain.img.url') . '/' . substr($img_uri, $is_uploads_dir + 9);
            }else{
                $img_url = substr($img_uri, $is_uploads_dir + 9);
            }
        }else{
            if (! empty($sub_dir) && ! empty($img_uri)){
                if ($is_full){
                    $img_url = C('domain.img.url') . '/' . $sub_dir . '/' . $img_uri;
                }else{
                    $img_url = $sub_dir . '/' . $img_uri;
                }
            }
            
        }
        return $img_url;
    }
}

/**
 * 显示图片优化函数
 */
if (! function_exists('optim_image')){
    function optim_image($img_full_url = '', $size = array(0, 0), $type = '', $watermark = FALSE){
        
        //地址为空 或者优化配置关闭直接返回 
        if ($img_full_url == '' || !C('images_optim.optim')){
            return $img_full_url;
        } 
        
        $extension =  substr($img_full_url, strrpos($img_full_url, '.'));
        
        $replace_str = '_t';
        if ($type){
            $replace_str .= $type;
        }
        if ($watermark){
            $replace_str .= '_w';
        }
        if ($size[0] && $size[1]){
            $replace_str .= '_s'.$size[0] . 'x' . $size[1];
        }
        
        $img_full_url = str_replace($extension, $replace_str . $extension,  $img_full_url);
        
        return $img_full_url;
    }
   
}

/**
 * 替换文章内容中图片为全路径
 *
 * @param string $content
 * @return mixed
 */

if (! function_exists('get_full_content_img_url')){
    function get_full_content_img_url($content){
        return  str_replace(array('/../Uploads/image/','/Uploads/'),  C('domain.img.url') . '/', $content);

    }
}

 


/**
 * 获取头像地址
 * 
 * @param string $img_uri
 * @param boolean  $is_full
 * @return string
 */

if (! function_exists('get_portrait_url')){
    function get_portrait_url($img_uri = '', $is_full = TRUE){
        $portrait_url = '';
        if($is_full){
            $portrait_url = C('domain.img.url') . '/'.'portrait/'.$img_uri;
        }else{
            $portrait_url = '/portrait/'.$img_uri;
        }
        return $portrait_url;
    }
}

/**
 * 获取css和js的url
 *
 * @param string $css_js_uri css或者js的uri
 *
 * @return string $css_js_url
 */
if (! function_exists('css_js_url')){
    function css_js_url($css_js_uri, $app_type){

        $static_url = C('domain.static.url');
        $type = 'css';
        if (strpos($css_js_uri, '.js') !== FALSE){
            $type = 'js';
        }

        //优先读取压缩过的文件
        $is_merge = FALSE;
        if (strpos($css_js_uri, ",") !== FALSE){
            $is_merge = TRUE;
        }

        $css_js_url_arr = explode(',', $css_js_uri);
        foreach ($css_js_url_arr as $key=>$v){
            if (strpos($v, '.min.') === FALSE)
            {
                $min_css_js_uri = str_replace('.' . $type, '.min.' . $type, $v);
                $min_static_file = C('css_js.static_path').'/' . $app_type . '/' . $type.'/'. $min_css_js_uri;
                if(file_exists($min_static_file)){
                    $css_js_url_arr[$key] = $min_css_js_uri;
                }
            }
        }


        $version = C('css_js_version')[$app_type][$type];
        //从数据库中查询版本号
        $CI = get_instance();
        $CI->db->from('t_version');
        $CI->db->where(['web_type' => $app_type]);
        $result = $CI->db->get();
        $version_result = $result->row();
        if($version_result){
	        $api_version = '';
	        if($type == 'css'){
	        	$api_version = $version_result->css_version_id;
	        }else{
	        	$api_version = $version_result->js_version_id;
	        }
	        $file = BASEPATH.'../shared/config/css_js_version.php';
	        $config_time = 0;
	        if(file_exists($file)){
		        $config_time = filemtime($file);
	        }
	        $database_time = strtotime($version_result->update_time);

	        //比较配置文件和数据库中的版本号，选取较大的一个
	        $version = intval($database_time) >= intval($config_time) ? $api_version :$version;
        }


        $css_js_uri = $type .'/'. implode(','. $type .'/', $css_js_url_arr);
        if ($is_merge){
            $css_js_url = $static_url . '/'. $app_type.'/??'. $css_js_uri . '?v='. $version;
        }else{
            $css_js_url = $static_url . '/'. $app_type.'/'. $css_js_uri . '?v='. $version;
        }

        return $css_js_url;
    }
}

/**
 * 返回CSS和JS导入文件
 *
 * @param string $css_js_uri css或者js的uri
 *
 * @return string $css_js_url
 **/

if (! function_exists('css_js_url_v2')){
    function css_js_url_v2($css_js_uri, $app_type) {
        $type = "css";
        $link_url = "";
        if (strpos($css_js_uri, '.js')){
            $type = 'js';
        }
      if($type == 'css'){
            $link_url = '<link href="%s" rel="stylesheet"> ';
        }
        else{
            $link_url = '<script src="%s"></script>';
        }
        if(@C('css_js.development')){ //线下
            $css_js_url_arr = explode(',', $css_js_uri);
            foreach ($css_js_url_arr as $key=>$v){
                printf($link_url, $string_url =  css_js_url($v, $app_type));
                echo "\n\r";
            }
        }else{ //线上
            $string_url =  css_js_url($css_js_uri, $app_type);
            printf($link_url,$string_url);
            echo "\n\r";
        }

    }
}

/**
 * 1034487709@qq.com
 * 操作日志
 * @param string $id 操作人ID
 * @param string $info 操作信息
 * @return void
 */
function operate_log($id , $info = ""){
    $CI = get_instance();
    $data = array(
        "operate_id" => $id,
        "operate_content" => $info,
        "create_time" => time()
    );
    $CI->db->insert("t_operate_log",$data);
  
}




/*
Utf-8、gb2312都支持的汉字截取函数
cut_str(字符串, 截取长度, 开始长度, 编码);
编码默认为 utf-8
开始长度默认为 0
*/
function cut_str($string, $sublen, $start = 0, $code = 'UTF-8')
{
    if($code == 'UTF-8')
    {
        $pa ="/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/";
        preg_match_all($pa, $string, $t_string);
        if(count($t_string[0]) - $start > $sublen)
            return join('', array_slice($t_string[0], $start, $sublen));

        return join('', array_slice($t_string[0], $start, $sublen));
    }
    else
    {
        $start = $start*2;
        $sublen = $sublen*2;
        $strlen = strlen($string);
        $tmpstr = '';
        for($i=0; $i<$strlen; $i++)
        {
            if($i>=$start && $i<($start+$sublen))
            {
                if(ord(substr($string, $i, 1))>129)
                {
                    $tmpstr.= substr($string, $i, 2);
                }
                else
                {
                    $tmpstr.= substr($string, $i, 1);
                }
            }
            if(ord(substr($string, $i, 1))>129)
                $i++;
        }
        return $tmpstr;
    }
}

/**
 * 获取随机奖品
 *
 * @param array $data  奖品数据
 *
 * @return number  中奖奖项
 */
if (! function_exists('get_rand')){
    function get_rand($data) {
        $result = '';
        $pro_sum = array_sum($data);
        foreach ($data as $key => $pro_cur) {
            $rand_num = mt_rand(1, $pro_sum);
            if ($rand_num <= $pro_cur) {
                $result = $key;
                break;
            } else {
                $pro_sum -= $pro_cur;
            }
        }
        unset ($data);
        return $result;
    }
}

/**
 * 是否移动设备访问
 * @author 1034487709@qq.com
 * @return boolean
 * @ruturn boolean
 */
if(!function_exists('ismobile')){
	function ismobile() {
		// 如果有HTTP_X_WAP_PROFILE则一定是移动设备
		if (isset ($_SERVER['HTTP_X_WAP_PROFILE']))
			return true;

		//此条摘自TPM智能切换模板引擎，适合TPM开发
		if(isset ($_SERVER['HTTP_CLIENT']) &&'PhoneClient'==$_SERVER['HTTP_CLIENT'])
			return true;
		//如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
		if (isset ($_SERVER['HTTP_VIA']))
			//找不到为flase,否则为true
			return stristr($_SERVER['HTTP_VIA'], 'wap') ? true : false;
		//判断手机发送的客户端标志,兼容性有待提高
		if (isset ($_SERVER['HTTP_USER_AGENT'])) {
			$clientkeywords = array(
					'nokia','sony','ericsson','mot','samsung','htc','sgh','lg','sharp','sie-','philips','panasonic','alcatel','lenovo','iphone','ipod','blackberry','meizu','android','netfront','symbian','ucweb','windowsce','palm','operamini','operamobi','openwave','nexusone','cldc','midp','wap','mobile'
			);
			//从HTTP_USER_AGENT中查找手机浏览器的关键字
			if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT']))) {
				return true;
			}
		}
		//协议法，因为有可能不准确，放到最后判断
		if (isset ($_SERVER['HTTP_ACCEPT'])) {
			// 如果只支持wml并且不支持html那一定是移动设备
			// 如果支持wml和html但是wml在html之前则是移动设备
			if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html')))) {
				return true;
			}
		}
		return false;
	}
}


/**
 *  删除非站内链接
 *
 * @access    public
 * @param     string  $body  内容
 * @param     array  $allow_urls  允许的超链接
 * @return    string
 */
if(!function_exists('replace_links')){
    function replace_links($body, $allow_urls=array('global28.com')){
        $host_rule = join('|', $allow_urls);
        $host_rule = preg_replace("#[\n\r]#", '', $host_rule);
        $host_rule = str_replace('.', "\\.", $host_rule);
        $host_rule = str_replace('/', "\\/", $host_rule);
        $arr = '';
        preg_match_all("#<a([^>]*)>(.*)<\/a>#iU", $body, $arr);
        if( is_array($arr[0]) )
        {
            $rparr = array();
            $tgarr = array();
            foreach($arr[0] as $i=>$v)
            {
                if( $host_rule != '' && preg_match('#'.$host_rule.'#i', $arr[1][$i]) )
                {
                    continue;
                } else {
                    $rparr[] = $v;
                    $tgarr[] = $arr[2][$i];
                }
            }
            if( !empty($rparr) )
            {
                $body = str_replace($rparr, $tgarr, $body);
            }
        }
        return $body;
    }

    /**
     * 获取客户端ip
     *
     */
    if(!function_exists('get_client_ip')){
        function get_client_ip($type = 0) {
            $type       =  $type ? 1 : 0;
            static $ip  =   NULL;
            if ($ip !== NULL) return $ip[$type];
            if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $arr    =   explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
                $pos    =   array_search('unknown',$arr);
                if(false !== $pos) unset($arr[$pos]);
                $ip     =   trim($arr[0]);
            }elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
                $ip     =   $_SERVER['HTTP_CLIENT_IP'];
            }elseif (isset($_SERVER['REMOTE_ADDR'])) {
                $ip     =   $_SERVER['REMOTE_ADDR'];
            }
            // IP地址合法验证
            $long = sprintf("%u",ip2long($ip));
            $ip   = $long ? array($ip, $long) : array('0.0.0.0', 0);
            return $ip[$type];
        }
    }

    /**
     * URL重定向
     * @param string $url 重定向的URL地址
     * @param integer $time 重定向的等待时间（秒）
     * @param string $msg 重定向前的提示信息
     * @return void
     */
    if(!function_exists('tp_redirect')){
        function tp_redirect($url, $time=0, $msg='') {
            //多行URL地址支持
            $url        = str_replace(array("\n", "\r"), '', $url);
            if (empty($msg))
                $msg    = "系统将在{$time}秒之后自动跳转到{$url}！";
            if (!headers_sent()) {
                // redirect
                if (0 === $time) {
                    header('Location: ' . $url);
                } else {
                    header("refresh:{$time};url={$url}");
                    echo($msg);
                }
                exit();
            } else {
                $str    = "<meta http-equiv='Refresh' content='{$time};URL={$url}'>";
                if ($time != 0)
                    $str .= $msg;
                exit($str);
            }
        }
    }


    /**
     *递归处理数组（将子分类与上级分类合并）
     *
     *@param string $data
     *@param string $parent
     *@param int page_number
     *@return array
     */
    if(!function_exists('class_loop')){
        function class_loop($data,$parent=0){

            $result = array();
            if($data)
            {
                foreach($data as $key=>$val)
                {
                    if($val['parent_id']==$parent)
                    {
                        $temp = class_loop($data,$val['id']);
                        if($temp) $val['child'] = $temp;
                        $result[] = $val;
                    }
                }
            }
            return $result;
        }
    }

    /**
     *递归处理数组（将子分类与上级分类合并）
     *
     *@param string $data
     *@param string $parent
     *@param int page_number
     *@return array
     */
    if(!function_exists('class_loop_list')) {
        function class_loop_list($data, $level = 0)
        {

            $level++;
            $result = array();
            if ($data) {
                foreach ($data as $v) {
                    $v['level'] = $level;
                    $child = array();
                    if (!empty($v['child'])) {
                        $child = $v['child'];
                    }
                    unset($v['child']);
                    $result[] = $v;
                    if (!empty($child)) {
                        $result = array_merge($result, class_loop_list($child, $level));
                    }
                }
            }
            return $result;
        }
    }

    /**
     *根据身份证号计算年龄
     *
     *@param string $birthday
     *@return int
     */
    if(!function_exists('get_age_by_ID')) {
        function get_age_by_ID($ID){ 
            if(empty($ID)) return ''; 
            $date = strtotime(substr($ID, 6, 8));
            $today = strtotime('today');
            $diff = floor(($today-$date)/86400/365);
            $age = strtotime(substr($ID, 6, 8).' +'.$diff.'years') > $today ? ($diff + 1) : $diff; 
            return $age; 
        } 
    }
    
    
    /**
     * 获取文章URL
     * 
     * @param number $id 文章id
     * @param string $is_full_url  是否带域名  TRUE-带域名  FALSE-不带域名
     */
    if(!function_exists('news_url')) {
        function news_url($id=0, $is_full_url = FALSE){
            
            if ($is_full_url){
                return C('domain.news.url').'/category/detail/'.$id.'.html';
            }
            return '/category/detail/'.$id.'.html';
            
        }
    }
   
    /**
     * 获取文章分类url
     * 
     * @param number $id 分类id
     * @param number $level 分类层级 1-一级分类  2-二级分类
     * @param string $is_full_url  是否带域名  TRUE-带域名  FALSE-不带域名
     */
    if(!function_exists('news_category_url')) {
        function news_category_url($id=0, $level=1,$is_full_url = FALSE){
            if ($is_full_url){
                return C('domain.news.url').'/category/c'.$level.'_'.$id.'.html';
            }
            return '/category/c'.$level.'_'.$id.'.html';
            
        }
    }
    
    /**
     * 获取楼盘url
     *
     * @param number $area_id  地区id
     * @param number $model_id 房型id
     * @param number $price_id 价格id
     * @param number $type     建筑类型id
     * @param number $character_id 特色id
     * @param number $time     时间(排序)id
     * @param number $sort     价格(排序)id
     * @param string $is_full_url  是否带域名  TRUE-带域名  FALSE-不带域名
     */
    if(!function_exists('news_loupan_url')) {
        function news_loupan_url($area_id=0, $model_id=0, $price_id=0, $type=0, $character_id=0, $time=0, $sort=0, $is_full_url = FALSE){
            if ($is_full_url){
                return C('domain.base.url').'/loupan/a'.$area_id.'_m'.$model_id.'_p'.$price_id.'_t'.$type.'_c'.$character_id.'_u'.$time.'_s'.$sort;
            }
            return '/loupan/a'.$area_id.'_m'.$model_id.'_p'.$price_id.'_t'.$type.'_c'.$character_id.'_u'.$time.'_s'.$sort;
    
        }
    }



    /**
     * 求两个日期之间相差的天数
     * @param string $day1
     * @param string $day2
     * @return number
     */
    if(!function_exists('diff_days')) {
        function diff_days ($day1, $day2) {
          $second1 = strtotime($day1);
          $second2 = strtotime($day2);
            
          if ($second1 < $second2) {
            $tmp = $second2;
            $second2 = $second1;
            $second1 = $tmp;
          }
          return ($second1 - $second2) / 86400;
        }
    }
    
    /**
     * @desc 获取oss图片地址
     */
    if(!function_exists('get_adv_img')){
        function get_adv_img($url, $style =""){
            $base = C('aliyunoss.bucket_domain');
            $url = $base.$url;
            if($style){
                $url .= '?x-oss-process=style/'.$style; 
            }
            return $url;
        }
    }
    
    /**
     * @desc 上传文件到oss
     * @param $bucket 
     * @param $loclFileName 文件所在路径
     * @param $ossFileName 上传oss后的路径与名称
     */
    if(!function_exists('upToOss')){
        function upToOss($bucket, $ossFileName, $loclFileName){
            if(empty($bucket)){
                return ['code' => 0, 'msg' => 'bucket不能为空'];
            }
            if(empty($ossFileName)){
                return ['code' => 0, 'msg' => '存储名称不能为空'];
            }
            if(!file_exists($loclFileName)){
                return ['code' => 0, 'msg' => '文件不存在'];
            }
            try {
                $config = C('aliyunoss');
                $ossclient = new OssClient($config['accessKeyId'], $config['accessKeySecret'], $config['endpoint_out']);
                try{
                    $ossclient->uploadFile($bucket, $ossFileName, $loclFileName);
                    return ['code' => 1, 'msg' => 'ok'];
                }catch (OssException $e){
                    return ['code' => 0, 'msg' => $e->getErrorMessage()];
                }
            }catch (OssException $e){
                return ['code' => 0, 'msg' => $e->getErrorMessage()];
            }
        }
    }
    
    /**
     * 返回配置文件一个固定格式的键值对数组
     */
    if(!function_exists('getConfig')){
        function getConfig($array){
            $data = [];
            if(empty($array)){
                return $data;
            }
            foreach ($array as $k => $v){
                $arr = ['index' => $k, 'reason' => $v];
                $data[] = $arr;
            }
            return $data;
        }
    }
    

    /**
     * 多维数组排序
     * @param arr $arrays
     * @param string $sort_key
     * @param string $SORT_ASC
     * @param string $SORT_ASC
     * @param string $sort_type
     * @return number
     */
    if(!function_exists('multi_arr_sort')) {
        function multi_arr_sort($arrays,$sort_key,$sort_order=SORT_ASC,$sort_type=SORT_NUMERIC){   
            if(is_array($arrays)){   
                foreach ($arrays as $array){   
                    if(is_array($array)){   
                        $key_arrays[] = $array[$sort_key];   
                    }else{   
                        return false;   
                    }   
                }   
            }else{   
                return false;   
            }  
            array_multisort($key_arrays,$sort_order,$sort_type,$arrays);   
            return $arrays;   
        } 
    }
    
}

