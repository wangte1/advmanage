<!-- 加载公用css -->
<?php $this->load->view('common/header');?>

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
                        <a href="#">Home</a>
                    </li>

                    <li>
                        <a href="/housesconfirm">派单确认</a>
                    </li>
                    
                    <li>
                        <span>验收图片</span>
                    </li>

                </ul>
                <div class="nav-search" id="nav-search">
                </div>
            </div>


            <div class="page-content">
				<div class="row" style="height:50px;padding-left: 12px;">
					<a href="javascript:;" class="btn btn-xs btn-info btn-export"  style="margin-bottom:10px">
                         <i class="fa fa-download out_excel" aria-hidden="true"></i> 导出点位
                    </a>
				</div>
                <div class="row">
                    <form action="" method="post">
                        <div class="col-xs-12 table-responsive">
                            <div class="row-fluid" style="margin-bottom: 100px;">
                                <table class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                        	<th  nowrap>点位编号</th>
                                        	<th class="center" nowrap>楼盘</th>
                                            <th class="center" nowrap>组团</th>
                                            <th class="center" nowrap>楼栋</th>
                                            <th class="center" nowrap>单元</th>
                                            <th class="center" nowrap>楼层</th>
                                            <th class="center" nowrap>点位位置</th>
                                            <th style="width:20%;" class="center" nowrap>图片</th>
                                        </tr>
                                    </thead>
                                    <tbody id="layer-photos-demo" class="layer-photos-demo">
                                        <?php if(isset($list))foreach ($list as $key => $value) :?>
                                        <tr>
                                        	<td  style="text-align: center;vertical-align: middle;"><?php echo $value['code'];?></td>
                                            <td  style="text-align: center;vertical-align: middle;"><?php echo $value['houses_name'];?></td>
                                            <td  style="text-align: center;vertical-align: middle;"><?php echo $value['houses_area_name'];?></td>
                                            <td  style="text-align: center;vertical-align: middle;"><?php echo $value['ban'];?></td>
                                            <td  style="text-align: center;vertical-align: middle;"><?php echo $value['unit'];?></td>
                                            <td  style="text-align: center;vertical-align: middle;"><?php echo $value['floor'];?></td>
                                            
                                            <td style="text-align: center;vertical-align: middle;"><?php if(isset($point_addr[$value['addr']])) echo $point_addr[$value['addr']];?></td>
                                            <td class="center">
                                                <?php 
                                                	switch ($value['status']){
                                                	    case 0 :
                                                	        $msg = "未上传未审核";
                                                	        break;
                                                	    case 1 :
                                                	        $msg = "<img style='width:120px;' src='".$value["no_img"]."' layer-src='".$value["no_img"]."' src='".$value["no_img"]."'/>";
                                                	        if($value['pano_img']){
                                                	            $str = "<img style='width:120px;' src='".$value["pano_img"]."' layer-src='".$value["pano_img"]."' src='".$value["pano_img"]."'/>";
                                                	            $msg = $msg.$str;
                                                	        }
                                                	        break;
                                                	    case 2 :
                                                	        $msg = "审核不通过";
                                                	        break;
                                                	}
                                                	echo $msg;
                                            	?>
                                            </td>
                                        </tr>
                                        <?php endforeach;?>
                                    </tbody>
                                </table>
                                
                                <!-- 分页 -->
                                <?php $this->load->view('common/page');?>
                            </div>
                        </div>
						
                    </form>

                </div>
            </div>
		</div>
	</div>
</div>
    

<!-- 加载尾部公用js -->
<?php $this->load->view("common/footer");?>
<script type="text/javascript">
    var baseUrl = "<?php echo $domain['admin']['url'];?>";
    var staticUrl = "<?php echo $domain['static']['url']?>";
    var mediaNum = "<?php echo count($list);?>";
</script>
<script src="<?php echo css_js_url('jquery.colorbox-min.js','admin');?>"></script>

<link href="<?php echo css_js_url('webuploader.css', 'common');?>" rel="stylesheet" />
<script src="<?php echo css_js_url('webuploader.js', 'common');?>"></script>


<script>

// 	$('img').on('click', function(){
// 		url = $(this).attr('src');
// 		window.open(baseUrl+url,'_blank');
// 	});
	
	var uploader_id;
	var uploader = WebUploader.create({
	
	    // 选完文件后，是否自动上传。
	    auto: true,

	    compress: {quality: 50},
	
	    // swf文件路径
	    swf: '<?php echo css_js_url('Uploader.swf', 'common');?>',
	
	    // 文件接收服务端。
	    server: baseUrl+"/fileupload/upload?dir=image",
	
	    // 选择文件的按钮。可选。
	    // 内部根据当前运行是创建，可能是input元素，也可能是flash.
	    pick: '.filePicker',
	
	    // 只允许选择图片文件。
	    accept: {
	        title: 'Images',
	        extensions: 'gif,jpg,jpeg,bmp,png',
	        mimeTypes: 'image/*'
	    }
	});

	var thumbnailWidth,thumbnailHeight = 100;
	// 当有文件添加进来的时候
	uploader.on( 'fileQueued', function( file ) {

	});

	// 文件上传过程中创建进度条实时显示。
	uploader.on( 'uploadProgress', function( file, percentage ) {

	});

	// 文件上传成功，给item添加成功class, 用样式标记上传成功。
	uploader.on( 'uploadSuccess', function( file, data) {
		var name = 'front_img';
        var media_id = $('#uploader_front_img'+uploader_id).attr('media-id');
		
        if(data.error == 0){
        	var html = '';
            html += "<li><a data-rel='colorbox' class='cboxElement' href='"+data.url+"'>";
            if(!isPc) {	//移动端
            	html += "<img src='"+data.url+"' style='width: 100px; height: 80px' />";
			}else {
				html += "<img src='"+data.url+"' style='width: 215px; height: 150px' />";
			}
            html += "</a>";
            html += ' <div class="tools"> <a href="javascript:;"> <i class="icon-remove red" onclick="$(this).parents(\'li\').remove();"></i> </a>  </div>';
            html += "<input type='hidden' name='"+media_id+"["+name+"][]' value='"+data.url+"'/></li>";
        }else {
            var html =     "<p>"+file.name+"上传异常</p>"
        }

        $('#uploader_front_img'+uploader_id).append(html);
        colorbox_init();
        if(!isPc) {	//移动端
        	$('.ace-thumbnails .tools').css('left','0');
		}
	});

	// 文件上传失败，显示上传出错。
	uploader.on( 'uploadError', function( file ) {
	    layer.alert('上传失败');
	});

	// 完成上传完了，成功或者失败，先删除进度条。
	uploader.on( 'uploadComplete', function( file ) {
	    //$( '#'+file.id ).find('.progress').remove();
	});

	$('.ace-thumbnails .icon-remove').on('click', function(){
        $(this).parents("li").remove();
    });

    $(function(){
    	colorbox_init();

    	if(!isPc) {	//移动端
        	$('.ace-thumbnails .tools').css('left','0');
		}
    });

    function colorbox_init() {
    	var colorbox_params = {
                reposition:true,
                scalePhotos:true,
                scrolling:false,
                previous:'<i class="icon-arrow-left"></i>',
                next:'<i class="icon-arrow-right"></i>',
                close:'&times;',
                current:'{current} of {total}',
                maxWidth:'100%',
                maxHeight:'100%',
                onOpen:function(){
                    document.body.style.overflow = 'hidden';
                },
                onClosed:function(){
                    document.body.style.overflow = 'auto';
                },
                onComplete:function(){
                    $.colorbox.resize();
                }
            };

            $('.ace-thumbnails [data-rel="colorbox"]').colorbox(colorbox_params);
            $("#cboxLoadingGraphic").append("<i class='icon-spinner orange'></i>");//let's add a custom loading icon
    }

</script>
<script type="text/javascript">
	$('.btn-export').on('click', function(){
		var url  = '/housesconfirm/user_all_task_export?id='+<?php echo $id;?>+"&type="+<?php echo $type;?>;
		window.location.href = url;
	});
</script>
<script>
    //调用示例
    layer.photos({
      photos: '#layer-photos-demo'
      ,anim: 5 //0-6的选择，指定弹出图片动画类型，默认随机（请注意，3.0之前的版本用shift参数）
    }); 
</script>
<!-- 底部 -->
<?php $this->load->view("common/bottom");?>
