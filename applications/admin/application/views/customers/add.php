<!-- 加载公用css -->
<?php $this->load->view('common/header');?>
<link href="<?php echo css_js_url('chosen.css', 'admin');?>" rel="stylesheet" />

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
                        <a href="#">管理员客户</a>
                    </li>
                    <li class="active">添加客户</li>
                </ul>
            </div>

            <div class="page-content">
                <div class="page-header">
                    <h1>
                        <h1>
                            添加客户
                        </h1>
                    </h1>
                </div>

                <div class="row">
                    <div class="col-xs-12">
                        <form  action="" method="post" class="form-horizontal" role="form">
                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 客户名称： </label>
                                <div class="col-sm-9">
                                    <input type="text" name="customer_name" required id="form-field-1" placeholder="客户名称" class="col-xs-10 col-sm-5">
                                   <span class="help-inline col-xs-12 col-sm-7 form-field-description-block">
                                       <span class="middle">* 最多可输入20个字符</span>
									</span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 客户类型： </label>
                                <div class="col-sm-9">
                                    <?php
                                    foreach($customer_type as $key=>$val){
                                    ?>
                                        <label>
                                            <input name="type" type="radio" class="ace" value="<?php echo $key;?>" <?php if($key ==1){echo "checked";}?> >
                                            <span class="lbl"> <?php echo $val;?></span>
                                        </label>
                                        &nbsp;&nbsp;
                                   <?php } ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 联系人： </label>
                                <div class="col-sm-9">
                                    <input type="text" name="contact_man" id="form-field-1" placeholder="请输入联系人" class="col-xs-10 col-sm-5">
                                    <span class="help-inline col-xs-12 col-sm-7 form-field-description-block">
                                       <span class="middle">* 最多可输入20个字符</span>
									</span>
                                </div>

                            </div>


                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1">  联系人手机号： </label>
                                <div class="col-sm-9">
                                    <input type="text" name="contact_mobile"  id="form-field-1" placeholder="请输入手机号" class="col-xs-10 col-sm-5">
                                     <span class="help-inline col-xs-12 col-sm-7 form-field-description-block">
                                       <span class="middle">* 输入准确的手机号便于联系</span>
									</span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1">  客户地址： </label>
                                <div class="col-sm-9">
                                    <input type="text" name="address"  id="form-field-1" placeholder="请输入客户地址" class="col-xs-10 col-sm-5">
                                    <span class="help-inline col-xs-12 col-sm-7 form-field-description-block">
                                       <span class="middle">* 请输入详细的地址便于联系</span>
									</span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1">  备注： </label>
                                <div class="col-sm-9">
                                    <textarea id="form-field-11" name="remark" placeholder="（选填）备注信息。最多300字。" class="autosize-transition col-xs-10 col-sm-3" style="overflow: hidden; word-wrap: break-word; resize: horizontal; height: 52px;"></textarea>
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
                    </div>
                </div>

            </div>
        </div>
</div>

<!-- 加载尾部公用js -->
<?php $this->load->view("common/footer");?>
<script type="text/javascript">
    $('#id-add-attachment').on('click', function() {
        var input = $('<input type="text" name="project_name[]" placeholder="请填写项目名称" class="col-sm-8" />').appendTo('#form-attachments');
        var wrapHtml = '<div class="row" style="margin-top: 9px"><div class="col-sm-7"></div></div>';
        var appendHtml = '<div class="action-buttons col-sm-1"><a href="#" data-action="delete" class="middle"><i class="icon-trash red bigger-130 middle"></i></a></div>';
        input.wrap(wrapHtml).parent().append(appendHtml).find('a[data-action=delete]').on('click', function(e) {
            e.preventDefault();
            $(this).closest('.row').hide(300, function() {
                $(this).remove();
            });
        });
    });
</script>

<!-- 底部 -->
<?php $this->load->view("common/bottom");?>

