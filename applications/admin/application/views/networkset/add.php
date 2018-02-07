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
                    <a href="/networkset">网络资源管理</a>
                </li>
                <li class="active">新增投放位置</li>
            </ul>
        </div>

        <div class="page-content">
            <div class="page-header">
                <h1>
                   新增投放位置
                    <a  href="/networkset" style="float: right; margin-right: 50px" class="btn btn-sm btn-primary">《返回列表页</a>
                </h1>
            </div>

            <div class="row">
                <div class="col-xs-12">
                    <form  action="" method="post" class="form-horizontal" role="form">


                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 投放位置： </label>
                            <div class="col-sm-9">
                                <input type="text" name="name" required id="form-field-1"  placeholder="" class="col-xs-10 col-sm-3">
                                <span class="help-inline col-xs-12 col-sm-7 form-field-description-block">
                                   <span class="middle" style="color: red">*</span> 最多可输入25个字符
								</span>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 所属tab： </label>
                            <div class="col-sm-9">
                            	<!-- <select class="col-xs-10 col-sm-3">
                                	<?php if(isset($modInfo)) {?>
	                                   	<?php foreach($modInfo as $k=>$v){?>
	                                    <option value="<?php echo $v['id'];?>"><?php echo $v['name'];?></option>
	                                    <?php }?>
                                    <?php }?>
                                </select> -->
                            
                                <select name="type" class="col-xs-10 col-sm-3">
                                	<?php if(isset($nettype)) {?>
                                		<?php foreach($nettype as $k=>$v) {?>
                                		<option value="<?php echo $v['id'];?>"><?php echo $v['name'];?></option>
                                		<?php }?>
                                	<?php }?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 广告形式： </label>
                            <div class="col-sm-9">
                                <input type="text" name="adform"   placeholder="" class="col-xs-10 col-sm-3">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 格式： </label>
                            <div class="col-sm-9">
                                <input type="text" name="format"  placeholder="" class="col-xs-10 col-sm-3">
                            </div>
                        </div>
                        
                       

                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1">  单价： </label>
                            <div class="col-sm-9">
                                <input type="text" name="unitprice"  />
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1">  总价： </label>
                            <div class="col-sm-9">
                                <input type="text" name="totalprice"  />
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1">  折扣： </label>
                            <div class="col-sm-9">
                                <input type="text" name="discount"  />
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1">  净价： </label>
                            <div class="col-sm-9">
                                <input type="text" name="netprice"  />
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1">  排序： </label>
                            <div class="col-sm-9">
                                <input type="text" name="sort"  />
                            </div>
                        </div>
                        
                        

                        <div class="clearfix form-actions">
                            <div class="col-md-offset-3 col-md-9">
                                <button class="btn btn-sm btn-info" type="submit">
                                    <i class="icon-ok bigger-110"></i>
                                   保存
                                </button>

                                &nbsp; &nbsp; &nbsp;
                                <button class="btn btn-sm" type="reset">
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

    <!-- 底部 -->
<?php $this->load->view("common/bottom");?>