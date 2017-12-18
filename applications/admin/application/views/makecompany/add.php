<!-- 加载公用css -->
<?php $this->load->view('common/header');?>
<style>
    #uploader_cover_img img{ width: 200px; height: 150px;}
    .ace-thumbnails>li {
        border: 1px solid rgb(241, 138, 27);
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
                        <a href="/makecompany">制作公司管理</a>
                    </li>
                    <li class="active">添加制作公司</li>
                </ul>

                <div class="nav-search" id="nav-search">
                    <form class="form-search">
                        <span class="input-icon">
                            <input type="text" placeholder="Search ..." class="nav-search-input" id="nav-search-input" autocomplete="off" />
                            <i class="icon-search nav-search-icon"></i>
                        </span>
                    </form>
                </div>
            </div>

            <div class="page-content">
                <div class="page-header">
                    <h1>
                        添加制作公司
                        <a href="/makecompany" class="btn btn-sm btn-primary pull-right">《返回列表页</a>
                    </h1>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <form class="form-horizontal" role="form" method="post" action="">
                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 公司名称： </label>
                                <div class="col-sm-9">
                                    <input type="text" name="company_name" placeholder="请填写公司名称" class="col-xs-10 col-sm-5" <?php if(isset($info['company_name'])): ?> value="<?php echo $info['company_name'];?>" readonly <?php endif;?> required />
                                    <span class="help-inline col-xs-12 col-sm-7 form-field-description-block">
                                       <span class="middle" style="color: red">*</span>
                                    </span>
                                </div>
                            </div>

                            <div class="space-4"></div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-2"> 联系人： </label>
                                <div class="col-sm-9">
                                    <input type="text" name="contact_man" placeholder="请填写联系人" class="col-xs-10 col-sm-5" value="<?php if(isset($info['contact_man'])){ echo $info['contact_man'];}?>" required />
                                </div>
                            </div>

                            <div class="space-4"></div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-input-readonly"> 联系人手机号： </label>
                                <div class="col-sm-9">
                                    <input type="text" name="contact_mobile" placeholder="请填写联系人手机号" class="col-xs-10 col-sm-5" value="<?php if(isset($info['contact_mobile'])){ echo $info['contact_mobile'];}?>" required />
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-input-readonly"> 座机： </label>
                                <div class="col-sm-9">
                                    <input type="text" name="tel" placeholder="请填写座机号码" class="col-xs-10 col-sm-5" value="<?php if(isset($info['tel'])){ echo $info['tel'];}?>" />
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-input-readonly"> 公司地址： </label>
                                <div class="col-sm-9">
                                    <input type="text" name="address" placeholder="请填写公司地址" class="col-xs-10 col-sm-5" value="<?php if(isset($info['address'])){ echo $info['address'];}?>" />
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-input-readonly"> 业务范围： </label>
                                <div class="col-sm-4">
                                    <textarea class="form-control" name="business_scope" rows="4" placeholder="业务范围，最多300个字"><?php if(isset($info['business_scope'])) { echo $info['business_scope'];}?></textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-input-readonly"> 备注： </label>
                                <div class="col-sm-4">
                                    <textarea class="form-control" name="remark" rows="4" placeholder="备注信息，最多300个字"><?php if(isset($info['remark'])) { echo $info['remark'];}?></textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-input-readonly"> 公司公章： </label>
                                <div class="col-sm-9">

                                    <ul class="ace-thumbnails" id="uploader_cover_img">
                                        <?php
                                        if(isset($info['seal_img'])&&!empty($info['seal_img'])){
                                       ?>
                                                <li data-id="4545">
                                                    <a href="<?php echo $info['seal_img'];?>" title="Photo Title" data-rel="colorbox" class="cboxElement">
                                                        <img alt="150x150" width="150px" height="150px" src="<?php echo $info['seal_img'];?>">

                                                    </a>
                                                    <div class="tools">
                                                        <a href="#">
                                                            <i class="icon-remove red"></i>
                                                        </a>
                                                    </div>
                                                    <input type="hidden" name="cover_img[]" value="<?php echo $info['seal_img'];?>"/>
                                                </li>
                                        <?php }?>

                                        <li class="pic pic-add add-pic" id="<?php if(isset($info['seal_img'])&&!empty($info['seal_img'])){ echo 'hidden-div';}?>" style="float: left;width: 220px;height: 150px;clear:none; border: 1px solid #f18a1b">
                                            <a href="javascript:;" class="up-img"  id="file_cover_img"><span>+</span><br>添加照片</a>

                                        </li>

                                    </ul>
                                </div>
                            </div>

                            <div class="space-4"></div>

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
                                    <button class="btn" type="button" onclick="javascript:history.go(-1)">
                                        <i class="fa fa-reply bigger-110"></i>
                                        返 回
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
