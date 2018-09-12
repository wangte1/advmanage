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
                    <a href="/housesinstall">楼盘安装管理</a>
                </li>
                <li class="active">编辑楼盘安装</li>
            </ul>
        </div>

        <div class="page-content">
            <div class="page-header">
                <h1>
                   编辑楼盘安装
                    <a  href="/housesinstall" style="float: right; margin-right: 50px" class="btn btn-sm btn-primary">《返回列表页</a>
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
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 物业联系人： </label>
                            <div class="col-sm-9">
                                <input type="text" name="linkman" value="<?php echo $info['linkman'];?>">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 联系人职务： </label>
                            <div class="col-sm-9">
                                <input type="text" name="linkman_duty" value="<?php echo $info['linkman_duty'];?>">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 联系人电话： </label>
                            <div class="col-sm-9">
                                <input type="text" name="linkman_tel" value="<?php echo $info['linkman_tel'];?>" onkeyup="(this.v=function(){this.value=this.value.replace(/[^0-9-]+/,'');}).call(this)" onblur="this.v();">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 签约数量： </label>
                            <div class="col-sm-9">
                                <input type="text" name="sign_num" value="<?php echo $info['sign_num'];?>" onkeyup="(this.v=function(){this.value=this.value.replace(/[^0-9-]+/,'');}).call(this)" onblur="this.v();">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-input-readonly"> 完工日期： </label>
                            <div class="col-sm-9" style="width: 15%";>
                                <div class="input-group date datepicker">
                                    <input class="form-control date-picker" type="text" name="finish_date" value="<?php if(isset($info['finish_date'])){ echo $info['finish_date'];}?>" >
                                    <span class="input-group-addon">
                                        <i class="icon-calendar bigger-110"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 安装数量： </label>
                            <div class="col-sm-9">
                                <input type="text" name="install_num" value="<?php echo $info['install_num'];?>" onkeyup="(this.v=function(){this.value=this.value.replace(/[^0-9-]+/,'');}).call(this)" onblur="this.v();">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 安装结算数量： </label>
                            <div class="col-sm-9">
                                <input type="text" name="install_account_num" value="<?php echo $info['install_account_num'];?>" onkeyup="(this.v=function(){this.value=this.value.replace(/[^0-9-]+/,'');}).call(this)" onblur="this.v();">
                            </div>
                        </div>

                        <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 安装备注： </label>
                                <div class="col-sm-9">
                                    <textarea name="install_remake" rows="2" class="autosize-transition col-xs-10 col-sm-3"><?php echo $info['install_remake'];?></textarea>
                                </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 验收人： </label>
                            <div class="col-sm-9">
                                <select class="select2" name="check_user" id="select-font-size " >
                                	<?php foreach ($admin as $k => $v):?>
                                		<option value="<?php echo $v['id']?>" <?php if($v['id'] == $info['check_user']) {?>selected="selected"<?php }?>><?php echo $v['fullname']?></option>
                                	<?php endforeach;?>
                                </select>
                            </div>
                        </div> 
                        
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-input-readonly"> 验收日期： </label>
                            <div class="col-sm-9" style="width: 15%";>
                                <div class="input-group date datepicker">
                                    <input class="form-control date-picker" type="text" name="check_date" value="<?php if(isset($info['check_date'])){ echo $info['check_date'];}?>" >
                                    <span class="input-group-addon">
                                        <i class="icon-calendar bigger-110"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-input-readonly"> 验收图片： </label>
                            <div class="col-sm-9">
                                <ul class="ace-thumbnails" id="uploader_cover_img">
                                	<?php if(!empty($info['check_img'])):?>
                                	<?php $tmp = explode(',', $info['check_img']);?>
                                	<?php foreach ($tmp as $k => $v):?>
                                	<li id="SWFUpload_0_0" class="pic pro_gre" style="margin-right: 20px; clear: none">
                                    	<a data-rel="colorbox" class="cboxElement" href="<?php echo $v;?>">
                                    		<img src="<?php echo $v;?>" style="width: 215px; height: 150px">
                                    	</a> 
                                    	<div class="tools"> 
                                    		<a href="javascript:;"> <i class="icon-remove red"></i> </a>  
                                    	</div>
                                    	<input type="hidden" name="cover_img[]" value="<?php echo $v;?>">
                                	</li>
                                	<?php endforeach;?>
                                	<?php endif;?>
                                	
                                    <li class="pic pic-add add-pic" style="float: left;width: 220px;height: 150px;clear:none; border: 1px solid #f18a1b">
                                        <a href="javascript:;" class="up-img"  id="file_cover_img"><span>+</span><br>添加照片</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-input-readonly"> 结算日期： </label>
                            <div class="col-sm-9" style="width: 15%";>
                                <div class="input-group date datepicker">
                                    <input class="form-control date-picker" type="text" name="account_date" value="<?php if(isset($info['account_date'])){ echo $info['account_date'];}?>" >
                                    <span class="input-group-addon">
                                        <i class="icon-calendar bigger-110"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 提成数量： </label>
                            <div class="col-sm-9">
                                <input type="text" name="push_num" value="<?php echo $info['push_num'];?>" onkeyup="(this.v=function(){this.value=this.value.replace(/[^0-9-]+/,'');}).call(this)" onblur="this.v();">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-input-readonly"> 提成日期： </label>
                            <div class="col-sm-9" style="width: 15%";>
                                <div class="input-group date datepicker">
                                    <input class="form-control date-picker" type="text" name="push_date" value="<?php if(isset($info['push_date'])){ echo $info['push_date'];}?>" >
                                    <span class="input-group-addon">
                                        <i class="icon-calendar bigger-110"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <!-- start -->
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 门禁卡数量： </label>
                            <div class="col-sm-9">
                                <input type="number" name="eg_card_num" value="<?php echo $info['eg_card_num'];?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 差额说明： </label>
                            <div class="col-sm-9">
                                <textarea name="balance_desc" rows="2" class="autosize-transition col-xs-10 col-sm-3"><?php echo $info['balance_desc'];?></textarea>
                                
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 验收备注： </label>
                            <div class="col-sm-9">
                            	<textarea name="check_desc" rows="2" class="autosize-transition col-xs-10 col-sm-3"><?php echo $info['check_desc'];?></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 安装进度： </label>
                            <div class="col-sm-9">
                                <input type="text" name="install_progress" value="<?php echo $info['install_progress'];?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 安装对接人： </label>
                            <div class="col-sm-9">
                                <input type="text" name="install_jointer" value="<?php echo $info['install_jointer'];?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 可安装时间： </label>
                            <div class="col-sm-9">
                                <input type="text" name="can_install_date" value="<?php echo $info['can_install_date'];?>">
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
<script src="<?php echo css_js_url('select2.min.js','admin');?>"></script>

<script type="text/javascript">
    var baseUrl = "<?php echo $domain['admin']['url'];?>";
    var staticUrl = "<?php echo $domain['static']['url']?>";
</script>
<script src="<?php echo css_js_url('jquery.colorbox-min.js','admin');?>"></script>
<script type="text/javascript" src="<?php echo css_js_url('jquery.swfupload.js', 'common');?>"></script>
<script type="text/javascript" src="<?php echo css_js_url('swfupload.js', 'admin')?>"></script>
<script type="text/javascript" src="<?php echo css_js_url('admin_upload.js', 'admin');?>"></script>

<script>
$(".select2").css('width','230px').select2({allowClear:true});
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