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
                        <a href="#">Home</a>
                    </li>

                    <li>
                        <a href="/adminspurview">权限管理</a>
                    </li>
                    <li class="active">添加权限</li>
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
                        添加权限
                        <a  href="/adminspurview" style="float: right; margin-right: 50px" class="btn btn-sm btn-primary">《返回列表</a>
                    </h1>
                </div>

                <div class="row">
                    <div class="col-xs-12">
                        <form  action="" method="post" class="form-horizontal" role="form">
                            <div class="form-group">
                                <label class="col-sm-1 control-label no-padding-right" for="form-field-1"> 上级分类： </label>

                                <div class="col-sm-9">
                                    <select class="col-xs-5" name="parent_id" id="form-field-select-1">
                                        <option value="0">顶级权限</option>
                                        <?php if($parent_purviews){?>
                                            <?php foreach($parent_purviews as $id=>$v){

                                                ?>
                                                <option value="<?php echo $v['id']?>" <?php if($v['id'] == $parent_id){?> selected="true" <?php }?>>
                                                    <?php echo  str_repeat("——",$v['level']).$v['name'];?>
                                                </option>


                                            <?php } ?>
                                        <?php }?>
                                    </select>
                                     <span class="help-inline col-xs-12 col-sm-7">
												<span class="middle" style="color: red">*</span>
									</span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-1 control-label no-padding-right" for="form-field-1"> 权限代码： </label>

                                <div class="col-sm-9">
                                    <input type="text" name="url" required id="form-field-1" placeholder="请输入权限代码" class="col-xs-10 col-sm-5">
                                    <span class="help-inline col-xs-12 col-sm-7">
												<span class="middle" style="color: red">*</span>
									</span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-1 control-label no-padding-right" for="form-field-1"> 权限名称： </label>

                                <div class="col-sm-9">
                                    <input type="text" name="name" required id="form-field-1" placeholder="请输入权限名称" class="col-xs-10 col-sm-5">
                                     <span class="help-inline col-xs-12 col-sm-7">
												<span class="middle" style="color: red">*</span>
								    </span>
                                </div>

                            </div>
                            <div class="form-group">
                                <label class="col-sm-1 control-label no-padding-right" for="form-field-1"> 排序： </label>

                                <div class="col-sm-9">
                                    <input type="text" name="sort" required id="form-field-1"  class="col-xs-10 col-sm-5">
                                     <span class="help-inline col-xs-12 col-sm-7">
												<span class="middle" style="color: red">*</span>
								    </span>
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

<!-- 底部 -->
<?php $this->load->view("common/bottom");?>
