<!--[if !IE]> -->
<script type="text/javascript">
    window.jQuery || document.write("<script src='<?php echo css_js_url("jquery-2.0.3.min.js","admin");?>'>"+"<"+"/script>");
</script>

<!-- <![endif]-->

<!--[if IE]>
<script type="text/javascript">

    window.jQuery || document.write("<script src='<?php echo css_js_url("jquery-1.10.2.min.js","admin");?>'>"+"<"+"/script>");
</script>
<![endif]-->

 <script>
 		var isPc = IsPC();
		$(function(){
			if(!isPc) {	//移动端
				$('#quick_menu').hide();
				$('#m-menu-button').show();
				$('#sidebar-collapse').hide();

				$('.phone-hide').hide();
				$('.phone-show').show();
				$('.breadcrumb').css('marginLeft', 0);
				
			}else {
				$('.phone-show').hide();
			}
		})
		
		function change_menu() {
			if($('#sidebar').css('display') == 'none') {
				$('#sidebar').show();
			}else {
				$('#sidebar').hide();
			}
		}
    
    	function IsPC() {
    	  var userAgentInfo = navigator.userAgent;
    	  var Agents = ["Android", "iPhone",
    	        "SymbianOS", "Windows Phone",
    	        "iPad", "iPod"];
    	  var flag = true;
    	  for (var v = 0; v < Agents.length; v++) {
    	    if (userAgentInfo.indexOf(Agents[v]) > 0) {
    	      flag = false;
    	      break;
    	    }
    	  }
    	  return flag;
    	}
</script>

<script type="text/javascript">
    if("ontouchend" in document) document.write("<script src='<?php echo css_js_url("jquery.mobile.custom.min.js","admin");?>'>"+"<"+"/script>");
</script>

<script src="<?php echo css_js_url('bootstrap.min.js','admin');?>"></script>
<script src="<?php echo css_js_url('typeahead-bs2.min.js','admin');?>"></script>

<script src="<?php echo css_js_url('jquery.dataTables.min.js','admin');?>"></script>
<script src="<?php echo css_js_url('jquery.dataTables.bootstrap.js','admin');?>"></script>


<script src="<?php echo css_js_url('ace-extra.min.js','admin');?>"></script>
<script src="<?php echo css_js_url('ace.min.js','admin');?>"></script>

<script src="<?php echo css_js_url('dialog.js','admin');?>"></script>

<script src="<?php echo css_js_url('public.js','admin');?>"></script>

<script src="<?php echo css_js_url('bootstrap-datepicker.js','common');?>"></script>
<script src="<?php echo css_js_url('bootstrap-datepicker.zh-CN.js','common');?>"></script>

<script src="<?php echo css_js_url('common.js','common');?>"></script>

<script src="<?php echo css_js_url('layer/layer.js','admin');?>"></script>


<!--[if lt IE 9]>
    <script src="<?php echo css_js_url('html5shiv.js','admin');?>"></script>
    <script src="<?php echo css_js_url('respond.min.js','admin');?>"></script>
<![endif]-->

<!-- data:2018-01-19 yonghua 254274509@qq.com -->

<script type="text/javascript">
	ws = new WebSocket("ws://60.205.184.98:8282");
    // 服务端主动推送消息时会触发这里的onmessage
    ws.onmessage = function(e){
        // json数据转换成js对象
        var data = eval("("+e.data+")");
        var type = data.type || '';

        switch(type){
            // Events.php中返回的init类型的消息，将client_id发给后台进行uid绑定
            case 'init':
                console.log(data.client_id);
                // 利用jquery发起ajax请求，将client_id发给后端进行uid绑定
                $.post('/bind/index', {client_id: data.client_id}, function(data){}, 'json');
                break;
            // 当mvc框架调用GatewayClient发消息时直接alert出来
            case 'ping':
                ws.send('{"type":"pong"}');
                break;
            case 'msg':
                layer.alert(data.message);
                break;
            default :
                
        }
    };
</script>
