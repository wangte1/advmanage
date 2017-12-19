<!-- 加载公用css -->
<?php $this->load->view('common/header');?>

<!-- 头部 -->
<?php $this->load->view('common/top');?>

<div class="main-container" id="main-container">
        <div class="main-container-inner">
            <?php $this->load->view("common/left");?>

        </div>

        <div class="main-content">
            <div class="breadcrumbs" id="breadcrumbs">
                <script type="text/javascript">
                    try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
                </script>

                <ul class="breadcrumb">
                    <li>
                        <i class="icon-home home-icon"></i>
                        <a href="#">Home</a>
                    </li>

                    <li>
                        <a href="/housespoints">楼盘点位管理</a>
                    </li>
                    <li class="active">编辑点位</li>
                </ul><!-- .breadcrumb -->


            </div>

            <div class="page-content">
                <div class="page-header">
                    <h1>
                       编辑点位
                        <a  href="/housespoints" style="float: right; margin-right: 50px" class="btn btn-sm btn-primary">《返回列表页</a>
                    </h1>
                </div><!-- /.page-header -->

                <div class="row">
                    <div class="col-xs-12">
                        <form id="add_form" action="" method="post" class="form-horizontal" role="form">


                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 点位编号： </label>

                                <div class="col-sm-9">
                                    <input type="text" id="code" name="code" required id="form-field-1" value="<?php echo $info['code'];?>" placeholder="请输入点位编号" class="col-xs-10 col-sm-3">
                                	<span class="help-inline col-xs-12 col-sm-7 form-field-description-block">
                                       <span class="middle" style="color: red" id="points_code_msg">*</span>
									</span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 点位类型： </label>

                                <div class="col-sm-9">
                                    <select class="select2" name="type_id">
                                    	<?php foreach ($tlist as $k => $v) {?>
                                    		<option value="<?php echo $v['type'];?>" <?php if($v['type'] == $info['type_id']) {?>selected="selected"<?php }?>><?php echo $order_type_text[$v['type']];?></option>
                                    	<?php }?>
                                    </select>
                                </div>
                            </div>
							
							<div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 所属地区： </label>
                                <div class="col-sm-9">
                                    <div id="distpicker1">
									  <select id="province"></select>
									  <select id="city"></select>
									  <select id="area"></select>
									</div>
                                </div>
                            </div>
                            
                            <div class="form-group" id="tishi" style="display: none">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"></label>

                                <div class="col-sm-9" id="address-info">
                                  <span class="help-inline col-xs-12 col-sm-7 form-field-description-block">
                                       <span class="middle" style="color:red"></span>
									</span>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 所属楼盘： </label>
                                <div class="col-sm-9">
                                    <select class="" name="houses_id" onchange="getArea();">
                                    	<?php foreach ($hlist as $k => $v) {?>
                                    		<option value="<?php echo $v['id'];?>" <?php if($v['id'] == $info['houses_id']) {?>selected="selected"<?php }?>><?php echo $v['name'];?></option>
                                    	<?php }?>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 所属区域： </label>
                                <div class="col-sm-9">
                                    <select class="" name="area_id">
                                    	<?php foreach ($alist as $k => $v) {?>
                                    		<option value="<?php echo $v['id'];?>" <?php if($v['id'] == $info['area_id']) {?>selected="selected"<?php }?>><?php echo $v['name'];?></option>
                                    	<?php }?>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 地址： </label>
                                <div class="col-sm-9">
                                	<input type="text" name="addr"  value="<?php echo $info['addr'];?>" class="col-xs-10 col-sm-3">
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-input-readonly"> 点位图： </label>
                                <div class="col-sm-9">

                                    <ul class="ace-thumbnails" id="uploader_cover_img">
                                        <?php if(isset($info['images']) && !empty($info['images'])):?>
                                        <?php foreach (explode(';', $info['images']) as $k => $v):?>
                                        <li id="SWFUpload_0_0" class="pic pro_gre" style="margin-right: 20px; clear: none">
                                            <a data-rel="colorbox" class="cboxElement" href="<?php echo $v?>">
                                            <img src="<?php echo $v?>" style="width: 215px; height: 150px"></a> 
                                            <div class="tools"> 
                                                <a href="javascript:;"> <i class="icon-remove red"></i> </a>
                                            </div>
                                            <input type="hidden" name="cover_img[]" value="<?php echo $v?>">
                                        </li>
                                        <?php endforeach;?>
                                        <?php endif;?>
                                        <li class="pic pic-add add-pic" id="<?php if(isset($info['seal_img'])&&!empty($info['seal_img'])){ echo 'hidden-div';}?>" style="float: left;width: 220px;height: 150px;clear:none; border: 1px solid #f18a1b">
                                            <a href="javascript:;" class="up-img"  id="file_cover_img"><span>+</span><br>添加照片</a>

                                        </li>

                                    </ul>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 展现形式： </label>
                                <div class="col-sm-9">
                                    <input type="text" name="show_method" value="<?php echo $info['show_method'];?>"  id="form-field-1" placeholder=""  class="col-xs-10 col-sm-3">
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 广告材质： </label>
                                <div class="col-sm-9">
                                    <input type="text" name="texture" value="<?php echo $info['texture'];?>"  id="form-field-1" placeholder=""  class="col-xs-10 col-sm-3">
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 点位价格： </label>
                                <div class="col-sm-9">
                                    <input type="text" id="price" name="price" required id="form-field-1" value="<?php if($info['price']) {echo $info['price'];}else {?>0.00<?php }?>" placeholder="" class="col-xs-10 col-sm-3">
                                   	<span class="help-inline col-xs-12 col-sm-7 form-field-description-block">
                                       <span class="middle">元/日 <i id="price_msg" style="font-style: normal"></i></span>
									</span>
                                </div>
                            </div>
                            
                            
                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1">  备注： </label>
                                <div class="col-sm-9">
                                    <textarea id="form-field-11" rows="5" name="remarks" placeholder="（选填）备注信息。最多200个字。" class="autosize-transition col-xs-10 col-sm-3" style="overflow: hidden; word-wrap: break-word; resize: horizontal;"></textarea>
                                </div>
                            </div>

                            <div class="clearfix form-actions">
                                <div class="col-md-offset-3 col-md-9">
                                    <button class="btn btn-info" type="button" id="subbtn">
                                        <i class="icon-ok bigger-110"></i>
                                        保存
                                    </button>

                                    &nbsp; &nbsp; &nbsp;
                                    <button class="btn" type="reset">
                                        <i class="icon-undo bigger-110"></i>
                                        重置
                                    </button>
                                </div>
                            </div>


                        </form>
                    </div><!-- /.col -->
                </div><!-- /.row -->

            </div><!-- /.page-content -->
        </div><!-- /.main-content -->



</div><!-- /.main-container -->



<!-- 加载尾部公用js -->
<?php $this->load->view("common/footer");?>


<script src="<?php echo css_js_url('select2.min.js','admin');?>"></script>

    <script type="text/javascript">
        $(function(){
            $(".select2").css('width','230px').select2({allowClear:true});

            $(".address").on('change', function(){
                var _obj = $(this);
                var str = "----请选择----";
                getinfo(_obj,str);
            });

            $("#distpicker1 select").change(function(){
				var province = $("#province").val();
				var city = $("#city").val();
				var area = $("#area").val();
				
				$.post('/housespoints/ajax_houses_info',{province:province,city:city,area:area},function(data){
					if(data) {
						var housesStr = '';
						for(var i = 0; i < data.length; i++) {

							if(i == 0) {
								housesStr += '<option value="' + data[i].id + '" selected = "selected">' + data[i].name + '</option>';
							}else {
								housesStr += '<option value="' + data[i].id + '">' + data[i].name + '</option>';
							}
						}
						
						$("select[name='houses_id']").html(housesStr);

						getArea();
					}
				});

				
            });


            $("#subbtn").click(function(){
//                 var points_code_type = parseInt($("#points_code_type").val());
//                 if(points_code_type == 1){
//                     $("#points_code").css("border-color","red");
//                     $("#points_code_msg").css("color","red").html("编号已经存在,请确保编号唯一性！");
//                     return false;
//                 }

            	if($("#code").val() == ""){
                    $("#code").css("border-color","red");
                    $("#points_code_msg").css("color","red").html("点位编号不能为空！");
                    return false;
                }

                //判断用户是否选择城市和地区
                if($("#province").val() == ""){
                    $("#tishi").slideDown().find(".middle").html("请选择省份!");
                    return false;
                }

                if($("#city").val() == ""){
                    $("#tishi").slideDown().find(".middle").html("请选择城市!");
                    return false;
                }
                if($("#area").val() == ""){
                    $("#tishi").slideDown().find(".middle").html("请选择地区!");
                    return false;
                }

                if($("#price").val() == ""){
                    $("#price").css("border-color","red");
                    $("#price_msg").css("color","red").html("点位价格不能为空！");
                    return false;
                }

                $("#add_form").submit();
            });
            
        });

        function getArea() {
            
        	var houses_id = $("select[name='houses_id']").val();

        	if(houses_id == null) {
        		$("select[name='area_id']").html("");
        		return;
            }
        	
        	$.post('/housespoints/ajax_area_info',{houses_id:houses_id},function(data){
				if(data) {
					var areaStr = '';
					for(var i = 0; i < data.length; i++) {

						if(i == 0) {
							areaStr += '<option value="' + data[i].id + '" selected = "selected">' + data[i].name + '</option>';
						}else {
							areaStr += '<option value="' + data[i].id + '">' + data[i].name + '</option>';
						}
					}
					
					$("select[name='area_id']").html(areaStr);

				}
			});
        }
     </script>

<script src="<?php echo css_js_url('jqdistpicker/distpicker.data.js','admin');?>"></script>
<script src="<?php echo css_js_url('jqdistpicker/distpicker.js','admin');?>"></script>

<script>
	$("#distpicker1").distpicker({
		province: "<?php echo $d_houses['province'];?>",
		city: "<?php echo $d_houses['city'];?>",
		district: "<?php echo $d_houses['area'];?>"
	});

	
</script>

<script type="text/javascript">
    var baseUrl = "<?php echo $domain['admin']['url'];?>";
    var staticUrl = "<?php echo $domain['static']['url']?>";
</script>
<script src="<?php echo css_js_url('jquery.colorbox-min.js','admin');?>"></script>
<script type="text/javascript" src="<?php echo css_js_url('jquery.swfupload.js', 'common');?>"></script>
<script type="text/javascript" src="<?php echo css_js_url('swfupload.js', 'admin')?>"></script>
<script type="text/javascript" src="<?php echo css_js_url('admin_upload.js', 'admin');?>"></script>
<script>
    $(function(){
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

        // 删除照片
        $("#uploader_cover_img").on("click",'.icon-remove',function(){
            $(this).parents("li").remove();
            $(".add-pic").show();
        });
    });
</script>

<!-- 底部 -->
<?php $this->load->view("common/bottom");?>