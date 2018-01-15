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
		$(function(){
			if(!IsPC()) {	//移动端
				$('#m-menu-button').show();
				$('#sidebar-collapse').hide();
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