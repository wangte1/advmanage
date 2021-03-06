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
                        <a href="/housesunit">单元管理</a>
                    </li>
                    <li class="active">新增单元</li>
                </ul><!-- .breadcrumb -->


            </div>

            <div class="page-content">
                <div class="page-header">
                    <h1>
                       新增单元
                        <a  href="/housesunit" style="float: right; margin-right: 50px" class="btn btn-sm btn-primary">《返回列表页</a>
                    </h1>
                </div><!-- /.page-header -->

                <div class="row">
                    <div class="col-xs-12">
                        <form  action="" method="post" class="form-horizontal" role="form">


                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 单元名称： </label>

                                <div class="col-sm-9">
                                    <input type="text" name="name" required id="form-field-1" placeholder="请输入单元名称" class="col-xs-10 col-sm-3">
                                    <span class="help-inline col-xs-12 col-sm-7 form-field-description-block">
                                       <span class="middle" style="color: red">*</span> 最多可输入100个字符
									</span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 所属楼盘： </label>

                                <div class="col-sm-9">
                                    <select class="select2" name="houses_id">
                                    	<option value="">请选择楼盘</option>
                                    	<?php foreach ($list as $k => $v) {?>
                                    		<option value="<?php echo $v['id'];?>"><?php echo $v['name'];?></option>
                                    	<?php }?>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 所属组团： </label>

                                <div class="col-sm-9">
                                    <select class="select2" name="group_id">
                                    	<option value="">请选择组团</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 所属楼栋： </label>

                                <div class="col-sm-9">
                                    <select class="select2" name="area_id">
                                    	<option value="">请选择楼栋</option>
                                    </select>
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
                                    <button class="btn btn-info" type="submit">
                                        <i class="icon-ok bigger-110"></i>
                                        添加
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


       	$(".select2").change(function(){
			var houses_id = $('select[name="houses_id"]').val();
			var group_id = $('select[name="group_id"]').val();
			
			if($(this).attr('name') == 'houses_id') {

				$('.select2-chosen:eq(1)').text('全部');
				$('.select2-chosen:eq(2)').text('全部');
				$.post('/housesunit/ajax_get_info', {'houses_id':houses_id,'group_id':group_id}, function(data) {
					if(data.group_arr) {
						var group_str = '<option value="">全部</option>';
						for(var i = 0; i < data.group_arr.length; i++) {
							group_str += '<option>'+(data.group_arr)[i]['group_name']+'</option>';
						}

						$('select[name="group_id"]').html(group_str);
					}

					if(data.area_arr) {
						var area_str = '<option value="">全部</option>';
						for(var i = 0; i < data.area_arr.length; i++) {
							area_str += '<option>'+(data.area_arr)[i]['name']+'</option>';
						}
						
						$('select[name="area_id"]').html(area_str);
					}
				});
			}


			if($(this).attr('name') == 'group_id') {

				$('.select2-chosen:eq(2)').text('全部');
				$.post('/housesunit/ajax_get_info', {'houses_id':houses_id,'group_id':group_id}, function(data) {
					if(data.area_arr) {
						var area_str = '<option value="">全部</option>';
						for(var i = 0; i < data.area_arr.length; i++) {
							area_str += '<option>'+(data.area_arr)[i]['name']+'</option>';
						}
						
						$('select[name="area_id"]').html(area_str);
					}
				});
			}

       });


       	
    });
</script>

<!-- 底部 -->
<?php $this->load->view("common/bottom");?>