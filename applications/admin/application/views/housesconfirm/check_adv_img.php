<!-- 加载公用css -->
<?php $this->load->view('common/header');?>

            <div class="page-content">

                <div class="row">
                    <form action="" method="post">
                        <div class="col-xs-12">
                            <div class="row-fluid">
                                <table class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                        	<th class="center phone-hide" nowrap>点位编号</th>
                                        	<th class="center phone-hide" nowrap>楼盘</th>
                                            <th class="center phone-hide" nowrap>组团</th>
                                            <th class="center phone-hide" nowrap>楼栋</th>
                                            <th class="center phone-hide" nowrap>单元</th>
                                            <th class="center phone-hide" nowrap>楼层</th>
                                            <th class="center phone-hide" nowrap>点位位置</th>
                                            <th class="center phone-show" nowrap>地址</th>
                                            <th class="center" nowrap>图片</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(isset($list))foreach ($list as $key => $value) :?>
                                        <tr>
                                        	<td class="phone-hide" style="text-align: center;vertical-align: middle;"><?php echo $value['code'];?></td>
                                            <td class="phone-hide" style="text-align: center;vertical-align: middle;"><?php echo $value['houses_name'];?></td>
                                            <td class="phone-hide" style="text-align: center;vertical-align: middle;"><?php echo $value['houses_area_name'];?></td>
                                            <td class="phone-hide" style="text-align: center;vertical-align: middle;"><?php echo $value['ban'];?></td>
                                            <td class="phone-hide" style="text-align: center;vertical-align: middle;"><?php echo $value['unit'];?></td>
                                            <td class="phone-hide" style="text-align: center;vertical-align: middle;"><?php echo $value['floor'];?></td>
                                            
                                            <td class="phone-hide" style="text-align: center;vertical-align: middle;"><?php if(isset($point_addr[$value['addr']])) echo $point_addr[$value['addr']];?></td>
                                            <td class="phone-show" nowrap>
                                            	<?php echo $value['houses_name'];?><br>
                                            	<?php echo $value['houses_area_name'];?><br>
                                            	<?php echo $value['ban'];?><br>
                                            	<?php echo $value['unit'];?><br>
                                            	<?php echo $value['floor'];?><br>
                                            	<?php if(isset($point_addr[$value['addr']])) echo $point_addr[$value['addr']];?>
                                            </td>
                                            <td class="">
                                                <div id="uploader-demo">
												    <div id="filePicker-<?php echo $key;?>" class="filePicker" onclick="uploader_id = <?php echo $key;?>;">选择图片</div>
												</div>
                                                
                                                <ul style="margin:0;padding:0;text-align:left;" class="ace-thumbnails" media-id="<?php echo $value['id'];?>" id="uploader_front_img<?php echo $key;?>">
                                                    <?php if(isset($value['image']) && count($value['image']) > 0): ?>
                                                        <?php foreach($value['image'] as $val):?>
                                                        <li>
                                                            <a href="<?php echo $val['front_img'];?>" title="Photo Title" data-rel="colorbox" class="cboxElement">
                                                                <img class="phone-hide" style="width: 215px; height: 150px" src="<?php echo $val['front_img'];?>">
                                                                <img class="phone-show" style="width: 100px; height: 80px" src="<?php echo $val['front_img'];?>">
                                                            </a>
                                                            <div class="tools">
                                                                <a href="javascript:;">
                                                                    <i class="icon-remove red"></i>
                                                                </a>
                                                            </div>
                                                            <input type="hidden" name="<?php echo $value['id'];?>[front_img][]" value="<?php echo $val['front_img'];?>"/>
                                                        </li>
                                                        <?php endforeach;?>
                                                    <?php endif;?>
                                                   
                                                </ul>
                                            </td>
                                        </tr>
                                        <?php endforeach;?>
                                    </tbody>
                                </table>
                                
                                <!-- 分页 -->
                                <?php $this->load->view('common/page');?>
                            </div>
                        </div>
						
                        <div class="col-xs-12" style="position: fixed; bottom: 0">
                            <div class="clearfix form-actions" style="padding: 0; margin-bottom: 0">
                                <div class="col-md-offset-5 col-md-7">
                                    <button class="btn btn-info" type="submit" id="subbtn">
                                        <i class="icon-ok bigger-110"></i>
                                        保存
                                    </button>
                                    <font style="color:red;">注：只能保存当前分页的上传</font>
                                </div>
                            </div>
                        </div>
                    </form>

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
	var uploader_id;
	var uploader = WebUploader.create({
	
	    // 选完文件后，是否自动上传。
	    auto: true,
	
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
<!-- 底部 -->
<?php $this->load->view("common/bottom");?>
