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
                    <li class="active">业务员管理</li>
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
                    <a href="/salesman/add" class="btn btn-sm btn-primary"><i class="fa fa-plus-square" aria-hidden="true"></i> 新增业务员</a>
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
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 姓名 </label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="name" value="<?php echo $name;?>" class="col-xs-10 col-sm-12" />
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 是否删除 </label>
                                                <div class="col-sm-3">
                                                    <select class="col-xs-12" name="is_del">
                                                        <option value="0" <?php if($is_del == 0) { echo "selected"; } ?>>未删除</option>
                                                        <option value="1" <?php if($is_del == 1) { echo "selected"; } ?>>已删除</option>
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
                    </div>

                    <div class="col-xs-12">
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="table-responsive">
                                    <table id="sample-table-1" class="table table-striped table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>编号</th>
                                                <th>姓名</th>
                                               <th>手机</th>
                                                <th>性别</th>
                                                <th>创建人</th>
                                                <th>创建时间</th>
                                                <th>备注</th>
                                                <th>操作</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($list as $key => $value) : ?>
                                            <tr>
                                                <td><?php echo $value['id'];?></td>
                                                <td><?php echo $value['name'];?></td>
                                                <td><?php echo $value['phone_number'];?></td>
                                                <td><?php echo $value['sex']==1?"男":"女";?></td>
                                                <td><?php echo $admins[$value['create_user']];?></td>
                                                <td><?php echo $value['create_time'];?></td>
                                                <td><?php echo $value['remark'];?></td>
                                                <td>
                                                    <div class="visible-md visible-lg hidden-sm hidden-xs action-buttons">
                                                        <a class="green tooltip-info" href="/salesman/edit/<?php echo $value['id'];?>" data-rel="tooltip" data-placement="top" data-original-title="修改">
                                                            <i class="icon-pencil bigger-130"></i>
                                                        </a>

                                                        <?php if($value['is_del'] == 0):?>
                                                        <a class="red tooltip-info del-spa" style="cursor: pointer" data-id="<?php echo $value['id'];?>" data-del="1" data-rel="tooltip" data-placement="top" data-original-title="删除">
                                                            <i class="icon-trash bigger-130"></i>
                                                        </a>
                                                        <?php else:?>
                                                        <a class="red tooltip-info" style="cursor: pointer" href="/salesman/del/<?php echo $value['id'];?>/0" data-rel="tooltip" data-placement="top" data-original-title="恢复">
                                                            <i class="fa fa-mail-reply"></i>
                                                        </a>
                                                        <?php endif;?>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>

                                    <!-- 分页 -->
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

<script type="text/javascript">
    $('.del-spa').click(function(){
        var _self = $(this);
        var d = dialog({
            title: "提示",
            content: '确定删除该业务员吗？',
            okValue: '确定',
            ok: function () {
                window.location.href = '/salesman/del/' + _self.attr('data-id') + '/' + _self.attr('data-del');
            },
            cancelValue: '取消',
            cancel: function () {}
        });
        d.width(320);
        d.showModal();
    });
</script>

<!-- 底部 -->
<?php $this->load->view("common/bottom");?>
