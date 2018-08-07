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
                        <a href="/housesagree">合同管理</a>
                    </li>
                    <li class="active">新增合同</li>
                </ul><!-- .breadcrumb -->


            </div>

            <div class="page-content">
                <div class="page-header">
                    <h1>
                       新增合同
                        <a  href="/housesagree" style="float: right; margin-right: 50px" class="btn btn-sm btn-primary">《返回列表页</a>
                    </h1>
                </div><!-- /.page-header -->

                <div class="row">
                    <div class="col-xs-12">
                        <form  action="" method="post" class="form-horizontal" role="form">
                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 物业公司： </label>
                                <div class="col-sm-9">
                                    <input type="text" name="pm_company" required id="form-field-1" placeholder="请输入物业公司名称" class="col-xs-10 col-sm-3">
                                    <span class="help-inline col-xs-12 col-sm-7 form-field-description-block">
                                       <span class="middle" style="color: red">*</span> 最多可输入100个字符
									</span>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 点位总数： </label>
                                <div class="col-sm-9">
                                    <input type="text" name="point_num" onkeyup="(this.v=function(){this.value=this.value.replace(/[^0-9-]+/,'');}).call(this)" onblur="this.v();">
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 地面点位数： </label>
                                <div class="col-sm-9">
                                    <input type="text" name="up_num" onkeyup="(this.v=function(){this.value=this.value.replace(/[^0-9-]+/,'');}).call(this)" onblur="this.v();">
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 地下点位数： </label>
                                <div class="col-sm-9">
                                    <input type="text" name="down_num" onkeyup="(this.v=function(){this.value=this.value.replace(/[^0-9-]+/,'');}).call(this)" onblur="this.v();">
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-input-readonly"> 合同开始时间： </label>
                                <div class="col-sm-9" style="width: 15%";>
                                    <div class="input-group date datepicker">
                                        <input class="form-control date-picker" type="text" name="agree_start_date">
                                        <span class="input-group-addon">
                                            <i class="icon-calendar bigger-110"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-input-readonly"> 合同结束时间： </label>
                                <div class="col-sm-9" style="width: 15%";>
                                    <div class="input-group date datepicker">
                                        <input class="form-control date-picker" type="text" name="agree_end_date">
                                        <span class="input-group-addon">
                                            <i class="icon-calendar bigger-110"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 开发负责人： </label>
                                <div class="col-sm-9">
                                    <input type="text" name="develer">
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 物业负责人： </label>
                                <div class="col-sm-9">
                                    <input type="text" name="property_owner">
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 负责人职务： </label>
                                <div class="col-sm-9">
                                    <input type="text" name="principal_duty">
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 负责人电话： </label>
                                <div class="col-sm-9">
                                    <input type="text" name="principal_tel" onkeyup="(this.v=function(){this.value=this.value.replace(/[^0-9-]+/,'');}).call(this)" onblur="this.v();">
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-input-readonly"> 签约日期： </label>
                                <div class="col-sm-9" style="width: 15%";>
                                    <div class="input-group date datepicker">
                                        <input class="form-control date-picker" type="text" name="sign_date">
                                        <span class="input-group-addon">
                                            <i class="icon-calendar bigger-110"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 楼盘名称：</label>
                                <div class="col-sm-9">
                                	<select id="select" class="select2" data-placeholder="Click to Choose..." name="">
                                		<option value="0">全部</option>
                                		<?php foreach ($hlist as $k => $v) {?>
                                    		<option value="<?php echo $v['id'];?>" <?php if($v['id'] == $name) {?>selected="selected"<?php }?>><?php echo $v['name'];?></option>
                                    	<?php }?>
                                    </select>
                                </div>
                            </div>
                            <div id="write" style="width: 400px;height:100px;margin-left: 20%;">
<!--                                 <button class="btn btn-xs btn-info do-sel" type="button" data-id="169">123 -->
<!--                                 	<i class="fa fa-remove" aria-hidden="true"></i> -->
<!--                             	</button> -->
                            </div>
                            
                            <div class="clearfix form-actions">
                                <div class="col-md-offset-3 col-md-9">
                                    <button id="sub-button" class="btn btn-info" type="button">
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

<script src="<?php echo css_js_url('jqdistpicker/distpicker.data.js','admin');?>"></script>
<script src="<?php echo css_js_url('jqdistpicker/distpicker.js','admin');?>"></script>
<script src="<?php echo css_js_url('select2.min.js','admin');?>"></script>

<script>
$(".select2").css('width','230px').select2({allowClear:true});
$("#select").change(function(){
// 	alert($('#select option:selected').val());
	var key = $('#select option:selected').val();
	var val = $('#select option:selected').text();
	var html = '<div class="ttbtn" style="float: left;"><button class="btn btn-xs btn-info do-sel" type="button" data-id="'+key+'">'+val+'<i class="fa fa-remove" aria-hidden="true"></i></button>&thinsp;&thinsp;<input type="hidden" name="housesarr[]" value="'+key+'"></div>';
	$("#write").append(html);
});
$("#write").on("click",".ttbtn",function(){
	$(this).remove();
});
	$("#distpicker1").distpicker({
		autoSelect: false,
		province: "贵州省",
		city: "贵阳市"
	});

	
	$(function(){
		$('#sub-button').click(function(){
			fun();
		});
	});

	function fun(){
	    obj = document.getElementsByName("sub_put_trade");
	    check_val = [];
	    for(k in obj){
	        if(obj[k].checked)
	            check_val.push(obj[k].value);
	    }

	    $('input[name="put_trade"]').val(check_val);
	    $('form').submit();
	}
</script>

<!-- 底部 -->
<?php $this->load->view("common/bottom");?>