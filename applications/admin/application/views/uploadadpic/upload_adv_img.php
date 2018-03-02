<!-- 加载公用css -->
<?php $this->load->view('common/header');?>
<style>
    #uploader_cover_img img{ width: 300px; height: 300px;}
    .ace-thumbnails>li {

       border: 1px solid #333;
    }
</style>
<!-- 头部 -->
<?php $this->load->view('common/top');?>

<div class="main-container" id="main-container">
    <div class="main-container-inner">
        <!-- 左边导航菜单 -->
        <?php $this->load->view('common/left');?>

        <div class="main-content">
            <div class="breadcrumbs" id="breadcrumbs">
                <ul class="breadcrumb">
                    <li>
                        <i class="icon-home home-icon"></i>
                        <a href="#">首页</a>
                    </li>
                    <li>
                        <a href="/orders">订单管理</a>
                    </li>
                    <li class="active">上传广告画面</li>
                </ul>

                <div class="nav-search" id="nav-search">
                    <form class="form-search">
                        <span class="input-icon">
                            <input type="text" placeholder="Search ..." class="nav-search-input" id="nav-search-input" autocomplete="off" />
                            <i class="icon-search nav-search-icon"></i>
                        </span>
                    </form>
                </div>
            </div>

            <div class="page-content">
                <div class="page-header">
                    <h1>上传广告画面</h1>
                    <a  href="javascript:history.back(-1);" style="float: right; margin-right: 50px" class="btn btn-sm btn-primary">《返回上一页</a>

                </div>
				
				<form class="form-horizontal" role="form" method="post" action="">
                    <div class="space-4"></div>
                                                                                
                	<div class="form-group" style="height:50px;">
	                    <label class="col-sm-2 control-label no-padding-right" for="form-field-2"> 是否打小样： </label>
	                    <div class="col-sm-10">
		                    <label class="blue">
		                        <input name="is_sample" value="1" type="radio" class="ace" <?php if(isset($info['is_sample']) && $info['is_sample'] == 1){ echo "checked"; }?> />
		                        <span class="lbl"> 是</span>
	                        </label>
	                        &nbsp;
	                       	<label class="blue">
		                       	<input name="is_sample" value="0" type="radio" class="ace" <?php if((isset($info['is_sample']) && $info['is_sample'] == 0) || !isset($info['is_sample'])){ echo "checked"; }?>>
		                       	<span class="lbl"> 否</span>
	                    	</label>
	                    </div>
                   	</div>
                   	
                   	<div class="form-group" style="height:400px;">
	                    <label class="col-sm-2 control-label no-padding-right" for="form-field-2"> 广告画面： </label>
	                    <div class="col-sm-10">
		                    <ul class="ace-thumbnails" id="uploader_cover_img">
	                            <li class="pic pic-add add-pic" style="float: left;width: 300px;height: 300px;clear:none; border: 1px solid #f18a1b">
	                            	<a href="javascript:;" class="up-img">
	                            		<img src="<?php if(isset($adv_img[0])) echo $adv_img[0];?>">
	                            	</a>
	                            </li>
                           	</ul>
                           	
                           	<?php if($order_status == 1) {?>
		                    <div class="col-xs-12">
		                        <label for="form-input-readonly"></label>
		                        <div style="margin-left: 5%;" id="file_cover_img">选择图片</div>
		                    </div>
		                    <?php }?>
	                    </div>
                   	</div>
                                        
					<?php if($order_status == 1) {?>
	                <div class="clearfix form-actions">
		                <div style="text-align: center;">
		                <button class="btn btn-info btn-save" type="submit">
		                <i class="icon-ok bigger-110"></i>
		                提交
		                </button>
		                                              
		                </div>
	                </div>
	                <?php }?>
                </form>
                
            </div>
        </div>
    </div>
</div>

<!-- 加载尾部公用js -->
<?php $this->load->view("common/footer");?>
<link href="<?php echo css_js_url('webuploader.css', 'common');?>" rel="stylesheet" />
<script src="<?php echo css_js_url('webuploader.js','common');?>"></script>
<script>

    var baseUrl = "<?php echo $domain['admin']['url'];?>";
	  //初始化Web Uploader
	var uploader = WebUploader.create({
	      // 选完文件后，是否自动上传。
	      auto: true,
	      // swf文件路径
	      swf: '<?php echo css_js_url('Uploader.swf', 'common');?>',
	   	  // 文件接收服务端。
	      server: baseUrl+"/file/upload?dir=image",
	      // 内部根据当前运行是创建，可能是input元素，也可能是flash.
	      pick:{
	          id:$("#file_cover_img"), // id
	          multiple: false  // false  单选 
	      },
	      // 只允许选择图片文件。
	      accept: {
	          title: 'Images',
	          extensions: 'gif,jpg,jpeg,bmp,png',
	          mimeTypes: 'image/*'
	      },
	      method:'POST',
	  });
	  //当有文件添加进来的时候
	  uploader.on( 'fileQueued', function( file ) {
	  });
	  //文件上传成功
	  uploader.on( 'uploadSuccess', function( file, res ) {
		var html = '<img src="'+res.url+'" />';
        $('.up-img').html(html);
		  
	  	if(res.error == 0){
	  		$('.up-img').append('<input type="hidden" name="cover_img" value="'+res.url+'"/>');
	  	}else{
	  		$('.up-img').html("");
	  		layer.msg('图片上传失败');
	  	}
	  });
    
</script>
<!-- 底部 -->
<?php $this->load->view("common/bottom");?>
