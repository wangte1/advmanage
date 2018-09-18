<script src="<?php echo css_js_url("jquery-2.0.3.min.js","admin");?>"></script>
<script src="<?php echo css_js_url('layer/layer.js','admin');?>"></script>
<script>
layer.alert('<?php echo $message?>',{
	icon:<?php echo $status?>
},function(){
	window.location.href="<?php echo $jumpUrl?>";
});
</script>