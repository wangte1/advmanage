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
                        <a href="/specification">规格管理</a>
                    </li>
                    <li class="active">添加规格</li>
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
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 规格类型： </label>
                                <div class="col-sm-9">
                                    <select class="col-xs-2" name="type" required>
                                        <option value="">请选择规格类型</option>
                                        <?php foreach(C('public.media_type') as $key=>$val){ ?>
                                        <option value="<?php echo $key;?>" <?php if(isset($info['type']) && $key == $info['type']) { echo "selected"; }?>><?php echo $val;?></option>
                                        <?php } ?>
                                    </select>
                                    <span class="help-inline col-xs-12 col-sm-7">
                                        <span class="middle" style="color: red">*</span>
                                    </span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-2"> 规格名称： </label>
                                <div class="col-sm-9">
                                    <input type="text" name="name" placeholder="请输入规格名称" class="col-xs-10 col-sm-5" value="<?php if(isset($info['name'])){ echo $info['name'];}?>" required />
                                    <span class="help-inline col-xs-12 col-sm-7">
                                        <span class="middle">例如：大灯箱、中灯箱、小灯箱等</span>
                                    </span>
                                </div>
                            </div>

                            <div class="space-4"></div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-input-readonly"> 规格尺寸： </label>
                                <div class="col-sm-9">
                                    <input type="text" name="size" placeholder="请输入规格尺寸" class="col-xs-10 col-sm-5" value="<?php if(isset($info['size'])){ echo $info['size'];}?>" required />
                                    <span class="help-inline col-xs-12 col-sm-7">
                                        <span class="middle">例如：3.5M*1.4M</span>
                                    </span>
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

<!-- 底部 -->
<?php $this->load->view("common/bottom");?>
