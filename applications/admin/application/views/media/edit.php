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
                    <a href="/mediamanage">媒体管理</a>
                </li>
                <li class="active">编辑媒体</li>
            </ul>
        </div>

        <div class="page-content">
            <div class="page-header">
                <h1>
                   编辑媒体
                    <a  href="/mediamanage" style="float: right; margin-right: 50px" class="btn btn-sm btn-primary">《返回列表页</a>
                </h1>
            </div>

            <div class="row">
                <div class="col-xs-12">
                    <form  action="" method="post" class="form-horizontal" role="form">


                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 名称： </label>
                            <div class="col-sm-9">
                                <input type="text" name="name" required id="form-field-1" value="<?php echo $info['name'];?>" placeholder="请输入媒体名称" class="col-xs-10 col-sm-3">
                                <span class="help-inline col-xs-12 col-sm-7 form-field-description-block">
                                   <span class="middle" style="color: red">*</span> 最多可输入100个字符
								</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 媒体编号： </label>
                            <div class="col-sm-9">
                                <input type="text" name="code" value="<?php echo $info['code'];?>" required id="form-field-1" placeholder="请输入媒体编号" class="col-xs-10 col-sm-3">
                                <span class="help-inline col-xs-12 col-sm-7 form-field-description-block">
                                   <span class="middle" style="color: red">*</span>
								</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 媒体新编号： </label>
                            <div class="col-sm-9">
                                <input type="text" name="new_code" value="<?php echo $info['new_code'];?>" id="form-field-1" placeholder="请输入媒体编号" class="col-xs-10 col-sm-3">
                                <span class="help-inline col-xs-12 col-sm-7 form-field-description-block">
                                   <span class="middle" style="color: red">*</span>
                                </span>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 媒体类型： </label>
                            <div class="col-sm-9">
                                <select class="col-xs-2 " name="type" id="select-font-size " >
                                    <?php foreach($media_type as $key=>$val){ ?>
                                        <option value="<?php echo $key;?>" <?php if($info['type'] == $key){ echo 'selected';}?> ><?php echo $val;?></option>
                                    <?php } ?>
                                </select>
                                 <span class="help-inline col-xs-12 col-sm-7">
											<span class="middle" style="color: red">*</span>
								</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1">  排序： </label>
                            <div class="col-sm-9">
                                <input type="number" name="sort" value="<?php echo $info['sort'];?>" />
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1">  备注： </label>
                            <div class="col-sm-9">
                                <textarea id="form-field-11" name="beizhu" placeholder="（选填）备注信息。最多300字。" class="autosize-transition col-xs-10 col-sm-3" style="overflow: hidden; word-wrap: break-word; resize: horizontal; height: 52px;"><?php echo $info['beizhu'];?></textarea>
                            </div>
                        </div>

                        <div class="clearfix form-actions">
                            <div class="col-md-offset-3 col-md-9">
                                <button class="btn btn-info" type="submit">
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

    <!-- 底部 -->
<?php $this->load->view("common/bottom");?>