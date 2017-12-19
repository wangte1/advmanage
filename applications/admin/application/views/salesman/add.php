<!-- 加载公用css -->
<?php $this->load->view('common/header');?>
<style>
    #uploader_cover_img img{ width: 200px; height: 150px;}
    .ace-thumbnails>li {
        border: 1px solid #333;
    }
</style>
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
                        <a href="#">首页</a>
                    </li>
                    <li>
                        <a href="#">资源管理</a>
                    </li>
                    <li>
                        <a href="/salesman">业务员管理</a>
                    </li>
                    <li class="active">新增业务员</li>
                </ul>


            </div>

            <div class="page-content">
                <div class="page-header">
                    <h1>
                        新增业务员
                        <a href="/salesman" class="btn btn-sm btn-primary pull-right">《返回列表页</a>
                    </h1>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <form class="form-horizontal" role="form" method="post" action="">
                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 姓名： </label>
                                <div class="col-sm-9">
                                    <input type="text" name="name" placeholder="请填写姓名" class="col-xs-10 col-sm-5" <?php if(isset($info['name'])): ?> value="<?php echo $info['name'];?>" readonly <?php endif;?> required />
                                    <span class="help-inline col-xs-12 col-sm-7 form-field-description-block">
                                       <span class="middle" style="color: red">*</span>
                                    </span>
                                </div>
                            </div>



                            <div class="space-4"></div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-input-readonly"> 手机号： </label>
                                <div class="col-sm-9">
                                    <input type="text" name="phone_number" placeholder="请填写联系人手机号" class="col-xs-10 col-sm-5" value="<?php if(isset($info['phone_number'])){ echo $info['phone_number'];}?>" required />
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-input-readonly">性别:</label>
                                <div class="col-sm-9">
                                    <label>
                                            <input name="sex" type="radio" class="ace" value="1" checked>
                                            <span class="lbl"> 男</span>
                                    </label>
                                    <label>
                                        <input name="sex" type="radio" class="ace" value="2" <?php if(isset($info['sex']) && $info['sex']==2){ echo "checked";}?>>
                                        <span class="lbl"> 女</span>
                                    </label>
                                </div>
                            </div>


                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1">  备注： </label>

                                <div class="col-sm-9">
                                    <textarea id="form-field-11" name="remark" placeholder="（选填）备注信息。最多300字。" class="autosize-transition col-xs-10 col-sm-3" style="overflow: hidden; word-wrap: break-word; resize: horizontal; height: 52px;"><?php if(isset($info['remark'])){ echo $info['remark'];}?></textarea>
                                </div>
                            </div>

                            <div class="clearfix form-actions">
                                <div class="col-md-offset-3 col-md-9">
                                    <?php if(isset($info['id'])):?>
                                    <input type="hidden" name="id" value="<?php echo $info['id'];?>" />
                                    <?php endif;?>

                                    <button class="btn btn-info" type="submit">
                                        <i class="icon-ok bigger-110"></i>
                                        保 存
                                    </button>

                                    &nbsp; &nbsp; &nbsp;
                                    <button class="btn" type="reset">
                                        <i class="icon-undo bigger-110"></i>
                                        重 置
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 加载尾部公用js -->
<?php $this->load->view("common/footer");?>

</script>
<!-- 底部 -->
<?php $this->load->view("common/bottom");?>
