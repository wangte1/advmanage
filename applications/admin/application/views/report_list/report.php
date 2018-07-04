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
    	<div style="margin: 0 auto;width: 50%;height: 80px;text-align:center;margin-top:20px;" class="check-area">
    		<p style="text-align: left;">是否新编号：</p>
    		<label>
                  <input type="radio"  name="is_new_code" checked class="is_new_code" value="0"> 否
            </label>
    		<label>
                  <input type="radio" name="is_new_code" class="is_new_code"  value="1"> 是
            </label>
            
    	</div>
    	<div id="new_code_area" style="margin: 0 auto;width: 50%;height: 40px;text-align:center;display:none;">
    		<div style="clear:both;margin-left: 0px;width: 118px;clear: both;">
    			<input type="text" name="code" class="code" placeholder="请输入新编号">
    			<input type="text" name="code2" class="code2" placeholder="请再次输入新编号">
    		</div>
    	</div>

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
        <div style="margin: 0 auto;width: 50%;height: 40px;text-align:center;margin-top:170px;" class="check-area">
        	<p style="text-align: left;">备注：<input type="text" id="remarks" name="remarks"></p>
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
	$('.is_new_code').on('click', function(){
		var is_new_code = $(this).val();
		if(is_new_code == 1){
			$('#new_code_area').show();
		}else{
			$('#new_code_area').hide();
		}
	});
	$('body').on('click', '.btn', function(){
		
		var id = $('#id').val();
		var remarks = $('#remarks').val();
		$('.is_new_code').each(function(){
			if($(this).prop('checked')){
				is_new_code = $(this).val();
			}
		});
		var new_code = $('.code').val();
		var new_code2 = $('.code2').val();
		if(is_new_code == 1){
			if(new_code == ""){
				layer.alert('您请选择了是，必须填新编号！');
				return;
			}
			if(new_code != new_code2){
				layer.alert('两次输入的编号不一致！');
				return;
			}
		}
		var repair_img = $('input[name="cover_img[]"]').val();
		if(!repair_img) {
			repair_img = "";
		}
		var post_data = {'id':id,'repair_img':repair_img, 'is_new_code':is_new_code, 'new_code':new_code, 'remarks':remarks};
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