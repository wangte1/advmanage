<!-- 加载公用css -->
<?php $this->load->view('common/header');?>

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
                        <a href="/customerprojects">客户项目管理</a>
                    </li>
                    <li class="active">添加项目</li>
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
                        添加规格
                        <a href="/specification" class="btn btn-sm btn-primary pull-right">《返回列表页</a>
                    </h1>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <form class="form-horizontal" role="form" method="post" action="">
                            <div class="space-4"></div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 所属客户： </label>
                                <div class="col-sm-9">
                                    <?php if(isset($info['id'])):?>
                                    <div style="margin-top: 4px"><?php echo $info['customer_name'];?></div>
                                    <?php else:?>
                                    <select name="customer_id" class="select2" required>
                                        <option value="">请选择客户</option>
                                        <?php foreach($customers_list as $val):?>
                                        <option value="<?php echo $val['id'];?>" <?php if(isset($info['customer_id']) && $val['id'] == $info['customer_id']){ echo "selected"; }?>><?php echo $val['customer_name'];?></option>
                                        <?php endforeach;?>
                                    </select>
                                    <span class="help-inline">
                                        <span class="middle" style="color: red">*</span>
                                    </span>
                                    <?php endif;?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-2"> 项目名称： </label>
                                <div class="col-sm-9">
                                    <input type="text" name="project_name" placeholder="请输入项目名称" class="col-xs-10 col-sm-5" value="<?php if(isset($info['project_name'])){ echo $info['project_name'];}?>" required />
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
<script src="<?php echo css_js_url('select2.min.js','admin');?>"></script>
<script type="text/javascript">
    $(".select2").css('width','230px').select2({allowClear:true});
</script>

<!-- 底部 -->
<?php $this->load->view("common/bottom");?>
