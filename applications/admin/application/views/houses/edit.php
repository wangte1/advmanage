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
                    <a href="/houses">楼盘管理</a>
                </li>
                <li class="active">编辑楼盘</li>
            </ul>
        </div>

        <div class="page-content">
            <div class="page-header">
                <h1>
                   编辑楼盘
                    <a  href="/houses" style="float: right; margin-right: 50px" class="btn btn-sm btn-primary">《返回列表页</a>
                </h1>
            </div>

            <div class="row">
                <div class="col-xs-12">
                    <form  action="" method="post" class="form-horizontal" role="form">

                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 楼盘名称： </label>

                            <div class="col-sm-9">
                                <input type="text" name="name" required id="form-field-1" value="<?php echo $info['name'];?>" class="col-xs-10 col-sm-3">
                                <span class="help-inline col-xs-12 col-sm-7 form-field-description-block">
                                   <span class="middle" style="color: red">*</span> 最多可输入100个字符
								</span>
                            </div>
                        </div>

                       	<div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 所属地区： </label>

                            <div class="col-sm-9">
                                <div id="distpicker1">
									<select name="province"></select>
									<select name="city"></select>
									<select name="area"></select>
								</div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 具体位置： </label>
                                <div class="col-sm-9">
                                    <textarea name="position" rows="2" class="autosize-transition col-xs-10 col-sm-3"><?php echo $info['position'];?></textarea>
                                </div>
                        </div>
                            
                        <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 规划入住户数： </label>
                                <div class="col-sm-9">
                                    <input type="text" name="households" value="<?php echo $info['households'];?>" onkeyup="(this.v=function(){this.value=this.value.replace(/[^0-9-]+/,'');}).call(this)" onblur="this.v();">&nbsp;&nbsp;户
                                </div>
                        </div>
                            
                        <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 建筑层数： </label>
                                <div class="col-sm-9">
                                    <input type="text" name="floor_num" value="<?php echo $info['floor_num'];?>" onkeyup="(this.v=function(){this.value=this.value.replace(/[^0-9-]+/,'');}).call(this)" onblur="this.v();">&nbsp;&nbsp;层
                                </div>
                        </div>
                            
                        <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 入住率： </label>
                                <div class="col-sm-9">
                                    <input type="text"  name="occ_rate" value="<?php echo $info['occ_rate'];?>"> &nbsp;&nbsp;× 100%
                                </div>
                        </div>
                        
                        <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 交付年份： </label>
                                <div class="col-sm-9">
                                    <input type="number"  name="deliver_year" value="<?php echo $info['deliver_year'];?>" onkeyup="(this.v=function(){this.value=this.value.replace(/[^0-9-]+/,'');}).call(this)" onblur="this.v();">&nbsp;&nbsp;年
                                </div>
                        </div>
                            
                        <!-- <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 单元数： </label>
                                <div class="col-sm-9">
                                    <input type="text" name="unit_rate" value="<?php echo $info['unit_rate'];?>">
                                </div>
                        </div>   -->  
						
						<div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 禁投放行业： </label>
                            <div class="col-sm-9">
                                <input name="put_trade" type="hidden" value="<?php echo $info['put_trade'];?>" />
                                <?php foreach($put_trade as $key=>$val){ ?>
	                                <label style="margin-right:10px;"><input name="sub_put_trade" type="checkbox" value="<?php echo $key;?>" <?php if(in_array($key,explode(",",$info['put_trade']))) {?>checked="checked"<?php }?> /><?php echo $val;?></label>
								<?php } ?>
                            </div>
                       </div>
						
                            
                        <!-- <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 楼盘类型： </label>
                            <div class="col-sm-9">
                                <select class="col-xs-2 " name="type" id="select-font-size " >
                                	<?php foreach($houses_type as $key=>$val){ ?>
                                    	<option value="<?php echo $key;?>" <?php if($key == $info['type']) {?>selected=selected<?php }?>><?php echo $val;?></option>
                               		<?php } ?>
                                </select>
                                <span class="help-inline col-xs-12 col-sm-7">
									<span class="middle" style="color: red">*</span>
								</span>
                            </div>
                        </div> -->
                       
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 楼盘等级： </label>
                            <div class="col-sm-9">
                                <select class="col-xs-2 " name="grade" id="select-font-size " >
                                	<?php foreach($houses_grade as $key=>$val){ ?>
                                    	<option value="<?php echo $key;?>" <?php if($key == $info['grade']) {?>selected=selected<?php }?>><?php echo $val;?></option>
                               		<?php } ?>
                                </select>
                                <span class="help-inline col-xs-12 col-sm-7">
									<span class="middle" style="color: red">*</span>
								</span>
                            </div>
                        </div> 
                        
                         <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 物业公司： </label>
                                <div class="col-sm-9">
                                    <input type="text"  name="property_company" value="<?php echo $info['property_company'];?>">
                                </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 安装公司： </label>
                            <div class="col-sm-9">
                                <select class="col-xs-2 " name="install" id="select-font-size " >
                                <option value="0">无	</option>
                                	<?php foreach($install as $key=>$val){ ?>
                                    	<option value="<?php echo $key;?>" <?php if($key == $info['install']) {?>selected=selected<?php }?>><?php echo $val;?></option>
                               		<?php } ?>
                                </select>
                                <span class="help-inline col-xs-12 col-sm-7">
									<span class="middle" style="color: red">*</span>
								</span>
                            </div>
                        </div> 
                            
                       	<div class="form-group">
	                       	<label class="col-sm-3 control-label no-padding-right" for="form-field-1">  备注： </label>
	                       	<div class="col-sm-9">
	                        	<textarea id="form-field-11" rows="5" name="remarks" placeholder="（选填）备注信息。最多200个字。" class="autosize-transition col-xs-10 col-sm-3" style="overflow: hidden; word-wrap: break-word; resize: horizontal;"><?php echo $info['remarks'];?></textarea>
	                        </div>
                        </div>

                        <div class="clearfix form-actions">
                            <div class="col-md-offset-3 col-md-9">
                                <button id="sub-button" class="btn btn-info" type="button">
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
                </div>
            </div>
        </div>
    </div>
</div>


<!-- 加载尾部公用js -->
<?php $this->load->view("common/footer");?>

<script src="<?php echo css_js_url('jqdistpicker/distpicker.data.js','admin');?>"></script>
<script src="<?php echo css_js_url('jqdistpicker/distpicker.js','admin');?>"></script>

<script>
	$("#distpicker1").distpicker({
		autoSelect: false,
		province: "<?php echo $info['province'];?>",
		city: "<?php echo $info['city'];?>",
		district: "<?php echo $info['area'];?>"
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