<!-- 加载公用css -->
<?php $this->load->view('common/header');?>
<!-- 加载尾部公用js -->
<?php $this->load->view("common/footer");?>
<style>
    textarea{margin-top:1%;width:50%;height:100px;}
</style>
<div style="width: 100%;height:100%;text-align: center;">
	<div class="form">
    	<input type="hidden" id="id" name="id" value="<?php echo $id?>"/>
    	<textarea id="destroy" name="destroy" rows="" cols="" placeholder="请输入点位报修的内容"></textarea>
    	<div style="margin: 0 auto;width: 50%;height: 100%;">
        	<ul  class="ace-thumbnails" id="uploader_cover_img">
                <li class="pic pic-add add-pic" style="float: left;width: 220px;height: 150px;clear:none; border: 1px solid #f18a1b">
                    <a href="javascript:;" class="up-img"  id="file_cover_img"><span>+</span><br>添加照片</a>
                </li>
            </ul>
        </div>
        <div style="clear: both;"><button style="width: 50%;margin-top:1%;" class="btn btn-info">提交</button></div>
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
	$('.btn').on('click', function(){
		var id = $('#id').val(); 
		var destroy = $('#destroy').val();
		var destroy_img = $('input[name="cover_img[]"]').val();
		if(destroy =="" ){layer.msg('请先填写报损说明');return;}
		if(destroy_img == "undefined") {destroy_img = ''}
		$.post('/housespoints/report_add', {'id':id, 'destroy':destroy, 'destroy_img':destroy_img}, function(data){
			if(data.code == 1){
				window.parent.location.reload(); //刷新父页面
				return;
			}
			layer.msg(data.msg);
		});
	});
</script>