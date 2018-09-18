<!-- 加载公用css -->
<?php $this->load->view('common/header');?>

<!-- 头部 -->
<div class="main-container" id="main-container">
        <div class="main-container-inner">
        </div>

            <div class="page-content">
                <div class="page-header">
                    <h1>
                       新增组团
                        <a  href="/housesarea" style="float: right; margin-right: 50px" class="btn btn-sm btn-primary">《返回列表页</a>
                    </h1>
                </div><!-- /.page-header -->

                <div class="row">
                    <div class="col-xs-12">
                        <form  action="" method="post" class="form-horizontal" role="form">


                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 组团名称： </label>

                                <div class="col-sm-9">
                                    <input type="text" name="name" required id="form-field-1" placeholder="请输入楼栋名称" class="col-xs-10 col-sm-3">
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
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 置业类型： </label>
                                <div class="col-sm-9">
                                    <select class="select2" name="zhiye_id">
                                    	<?php foreach (C('zhiye') as $k => $v) {?>
                                    		<option value="<?php echo $k;?>"><?php echo $v;?></option>
                                    	<?php }?>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 组团等级： </label>
                                <div class="col-sm-9">
                                    <select class="col-xs-2 " name="grade" id="select-font-size " >
                                        <?php foreach($area_grade as $key=>$val){ ?>
                                            <option value="<?php echo $key;?>"><?php echo $val;?></option>
                                        <?php } ?>
                                    </select>
                                    <span class="help-inline col-xs-12 col-sm-7">
										<span class="middle" style="color: red">*</span>
									</span>
                                </div>
                            </div>
                            
                            <!-- <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 坐标： </label>
                                <div class="col-sm-9">
                                    <input type="text" class="col-xs-10 col-sm-3" name="coordinate">
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 腾讯坐标： </label>
                                <div class="col-sm-9">
                                    <input type="text" class="col-xs-10 col-sm-3" name="t_coordinate">
                                </div>
                            </div> -->
                            
                            
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
    });
</script>

<!-- 底部 -->
<?php $this->load->view("common/bottom");?>