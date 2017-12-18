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
                        <a href="#">Home</a>
                    </li>

                    <li>
                        <a href="#">资源管理</a>
                    </li>

                </ul>
                <div class="nav-search" id="nav-search">
                    <form class="form-search" action="/mediamanage/index" method="get">
						<span class="input-icon">
							<input type="text" name="name" placeholder="请输入站台名称...." value="<?php echo $name;?>" class="nav-search-input" id="nav-search-input" autocomplete="off" />
							<i class="icon-search nav-search-icon"></i>
						</span>
                    </form>
                </div>
            </div>

            <div class="page-content">
                <div class="page-header">
                    <a href="/mediamanage/add" class="btn btn-sm btn-primary"><i class="fa fa-plus-square" aria-hidden="true"></i> 添加媒体</a>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <div class="widget-box">
                            <div class="widget-header">
                                <h4>筛选条件</h4>
                                <div class="widget-toolbar">
                                    <a href="#" data-action="collapse">
                                        <i class="icon-chevron-up"></i>
                                    </a>
                                </div>
                            </div>

                            <div class="widget-body">
                                <div class="widget-main">
                                    <form class="form-horizontal" role="form">
                                        <div class="form-group">
                                            <div class="col-sm-4">
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 媒体编号 </label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="code" value="<?php echo $med_code;?>"  class="col-xs-10 col-sm-12" />
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 媒体名称 </label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="name" value="<?php echo $name;?>"  class="col-xs-10 col-sm-12" />
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 媒体类型 </label>
                                                <div class="col-sm-9">
                                                    <select id="state" name="type"  class="select2" data-placeholder="Click to Choose...">
                                                        <option value="all" <?php if($type == 'all'){ echo 'selected'; }?>>全部</option>
                                                        <?php foreach(C('public.media_type') as $key=>$val){ ?>
                                                            <option value="<?php echo $key;?>" <?php if($type != 'all' && ($key == $type || $key == 1)) { echo "selected"; }?>><?php echo $val;?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="clearfix form-actions">
                                            <div class="col-md-offset-3 col-md-9">
                                                <button class="btn btn-info" type="submit">
                                                    <i class="fa fa-search"></i>
                                                    查询
                                                </button>
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

                        <!-- PAGE CONTENT BEGINS -->
                        <div class="row">
                            <div class="col-xs-12">
                                 <div class="table-responsive">
                                    <table id="sample-table-2" class="table table-striped table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>媒体编号</th>
                                                <th>新编号</th>
                                                <th>名称</th>
                                                <th>媒体类型</th>
                                                <th>排序</th>
                                                <th>备注</th>
                                                <th>操作</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        if($list){
                                            foreach($list as $key=>$val){
                                                ?>
                                                <tr>
                                                    <td><a href=""><?php echo $val['code'];?></a></td>
                                                    <td><a href=""><?php echo $val['new_code'];?></a></td>
                                                    <td><?php echo $val['name'];?></td>
                                                    <td><?php echo $media_type[$val['type']];?></td>
                                                    <td><?php echo $val['sort'];?></td>
                                                    <td class="hidden-480">
                                                        <?php echo $val['beizhu'];?>
                                                    </td>

                                                    <td>
                                                        <div class="visible-md visible-lg hidden-sm hidden-xs action-buttons">
                                                            <a class="green tooltip-info" href="/mediamanage/edit/<?php echo $val['id'];?>" data-rel="tooltip" data-placement="top" data-original-title="修改">
                                                                <i class="icon-pencil bigger-130"></i>
                                                            </a>
                                                           <a class="red tooltip-info del" href="javascript:;" data-url="/mediamanage/del/<?php echo $val['id']?>" data-id="<?php echo $val['id'];?>" data-rel="tooltip" data-placement="top" data-original-title="删除">
                                                                <i class="icon-trash bigger-130"></i>
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php } }?>
										</tbody>
                                    </table>
									<!--分页start-->
                                    <?php $this->load->view('common/page');?>
                                </div>
                            </div>
                        </div>
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
    $(function(){
       $(".select2").css('width','230px').select2({allowClear:true});
    });
</script>
<!-- 底部 -->
<?php $this->load->view("common/bottom");?>