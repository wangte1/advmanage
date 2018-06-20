<!-- 加载公用css -->
<?php $this->load->view('common/header');?>
<!-- 加载尾部公用js -->
<?php $this->load->view("common/footer");?>
<style>
    textarea{clear:both;margin-top:1%;margin-bottom:1%;width:50%;height:100px;}
    label{float:left;margin-right:4%;}
</style>
<div style="width: 100%;height:100%;text-align: center;">
	<div class="form">
    	<input type="hidden" id="id" name="id" value="<?php echo $id?>"/>
    	<div style="margin: 0 auto;width: 50%;height: 40px;text-align:center;margin-top:20px;" class="check-area">
    		<p style="text-align: left;">请上传修复图：</p>
    	</div>
    	<div style="margin: 0 auto;width: 50%;height: 100%;">
        	<ul  class="ace-thumbnails" id="uploader_cover_img">
                <li class="pic pic-add add-pic" style="float: left;width: 220px;height: 150px;clear:none; border: 1px solid #f18a1b">
                    <a href="javascript:;" class="up-img"  id="file_cover_img"><span>+</span><br>修复图片</a>
                </li>
            </ul>
        </div>
        <div style="clear: both;"><button style="width: 50%;margin-top:1%;" class="btn btn-info">确认修复</button></div>
		<br/>
	</div>
</div>
<script type="text/javascript">
    var baseUrl = "<?php echo $domain['admin']['url'];?>";
    var staticUrl = "<?php echo $domain['static']['url']?>";
</script>
<script src="<?php echo css_js_url('jquery.colorbox-min.js','admin');?>"></script>
<script type="text/javascript" src="<?php echo css_js_url('jquery.swfupload.js', 'common');?>"></script>
<script type="text/javascript" src="<?php echo css_js_url('swfupload.js', 'admin')?>"></script>
<script type="text/javascript" src="<?php echo css_js_url('admin_upload.js', 'admin');?>"></script>
<script>
	$('body').on('click', '.btn', function(){
		
		var id = $('#id').val();
		var repair_img = $('input[name="cover_img[]"]').val();
		if(!repair_img) {
			layer.msg('修复图必须上传');return;
		}
		var post_data = {'id':id,'repair_img':repair_img};
		$.post('/report_list/report_add', post_data, function(data){
			if(data.code == 1){
				layer.alert(data.msg, function(){
					window.parent.location.reload(); //刷新父页面
					return;
				});
			}else{
				layer.msg(data.msg);
			}
		});
	});
</script>