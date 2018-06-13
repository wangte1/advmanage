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
    	<div style="margin: 0 auto;width: 50%;height: 120px;text-align:center;margin-top:20px;" class="check-area">
    		<p style="text-align: left;">报损选项：</p>
    		<?php foreach (C('housespoint.report') as $k => $v):?>
    		<?php if($k!=14):?>
    		<label>
                  <input type="checkbox" class="report_option" name="report[]" value="<?php echo $k;?>"> <?php echo $v;?>
            </label>
            <?php endif;?>
            <label>
                  <input type="checkbox" class="report_option" name="report[]" value="14"> <?php echo $v;?>
            </label>
    		<?php endforeach;?>
            
    	</div>
    	<br/>
    	
    	<div style="margin: 0 auto;width: 50%;height: 80px;text-align:center;margin-top:20px;" class="check-area">
    		<p style="text-align: left;">是否可以上画：</p>
    		<label>
                  <input type="radio" class="usable" name="usable" value="0"> 不可以
            </label>
    		<label>
                  <input type="radio" class="usable" name="usable" value="1"> 可以
            </label>
            
    	</div>
    	<textarea id="report_msg" name="report_msg" rows="" cols="" placeholder="请输入其他选项报修的内容"></textarea>
    	<br/>
    	<div style="margin: 0 auto;width: 50%;height: 100%;">
        	<ul  class="ace-thumbnails" id="uploader_cover_img">
                <li class="pic pic-add add-pic" style="float: left;width: 220px;height: 150px;clear:none; border: 1px solid #f18a1b">
                    <a href="javascript:;" class="up-img"  id="file_cover_img"><span>+</span><br>添加照片</a>
                </li>
            </ul>
        </div>
        <div style="clear: both;"><button style="width: 50%;margin-top:1%;" class="btn btn-info">提交</button></div>
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
	$('.btn').on('click', function(){
		
		var id = $('#id').val(); 
		var report_msg = $('#report_msg').val();
		var report = [];
		$('.report_option').each(function(){
			if($(this).prop('checked')){
				report.push($(this).val());
			}
		});
		if(report.length == 0){
			layer.alert('请至少选一个选项');return;
		}
		var report_img = $('input[name="cover_img[]"]').val();
		if(report_img == "undefined" || report_img =="") {report_img = ''}
		for(var i = 0; i < report.length; i++){
			if(report[i] == 14){
				if(report_msg == ''){
					layer.alert('您选择了其他的选项，请填写具体的内容');
					return;
				}
			}
		}
		var usable = -1;
		$('.usable').each(function(){
			if($(this).prop('checked')){
				usable = $(this).val();
			}
		});
		if(usable == -1){
			layer.alert('请选择一个是否可以上画的选项');
			return;
		}
		var post_data = {'id':id, 'report':report, 'report_msg':report_msg, 'report_img':report_img, 'usable':usable};
		$.post('/housespoints/report_add', post_data, function(data){
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