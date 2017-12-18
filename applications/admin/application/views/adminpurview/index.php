<!-- 加载公用css -->
<?php $this->load->view('common/header');?>
<link href="<?php echo css_js_url('style.css', 'admin');?>" rel="stylesheet" />
<style>
    td{ border: 1px solid #EDF6FA}
    #son-child td{ border: 0px; text-align: left; /*padding-left: 5px;*/}
    #son td{ text-align: left; /*padding: 0px 10px;*/}
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
                        <a href="#">Home</a>
                    </li>

                    <li>
                        <a href="#">管理员</a>
                    </li>
                    <li class="active">权限列表</li>
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
                    <a  href="/adminspurview/add" class="btn btn-sm btn-primary"><i class="fa fa-plus-square" aria-hidden="true"></i> 添加权限</a>
                </div> 

                <div class="row">
                    <div class="col-xs-12">
                        <table class="tablelist ">
                            <thead>
                            <tr>
                                <th width="15%">项目</th>
                                <th width="85%">
                                    <table width="100%">
                                        <tr>
                                            <td style="text-align: center; width: 10%;">权限</td>
                                            <td style="text-align: center">子权限</td>
                                        </tr>
                                    </table>
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if($list){?>
                                <?php foreach($list as $key=>$val){?>
                                    <tr>
                                        <td>
                                            <?php echo $val['name'];?>
                                            <a href="/adminspurview/edit/<?php echo $val['id']?>" title="编辑"><img src="<?php echo $domain['static']['url'];?>/admin/images/icon_edit.gif" /></a>
                                            <a href="/adminspurview/del/<?php echo $val['id']?>" title="删除"><img src="<?php echo $domain['static']['url'];?>/admin/images/icon_drop.gif" /></a>
                                            <a href="/adminspurview/add/<?php echo $val['id']?>" title="添加"><img src="<?php echo $domain['static']['url'];?>/admin/images/icon_add.gif" /></a>
                                        </td>
                                        <td>
                                            <table id="son_child" width="100%">
                                                <?php if(@$val['child']){?>
                                                    <?php foreach(@$val['child'] as $k=>$v){?>
                                                        <tr>
                                                            <td>
                                                                <table id="son">
                                                                    <tr>
                                                                        <td>
                                                                            <?php echo $v['name'];?>
                                                                            <a href="/adminspurview/edit/<?php echo $v['id']?>" title="编辑"><img src="<?php echo $domain['static']['url'];?>/admin/images/icon_edit.gif" /></a>
                                                                            <a href="/adminspurview/del/<?php echo $v['id']?>" title="删除"><img src="<?php echo $domain['static']['url'];?>/admin/images/icon_drop.gif" /></a>
                                                                            <a href="/adminspurview/add/<?php echo $v['id']?>" title="添加"><img src="<?php echo $domain['static']['url'];?>/admin/images/icon_add.gif" /></a>
                                                                        </td>
                                                                        <?php  if(@$v['child']){ ?>
                                                                            <td>
                                                                                <table id="son-child">
                                                                                    <tr>
                                                                                        <?php
                                                                                        if(@$v['child']){foreach(@$v['child'] as $kk=>$vv){  ?>
                                                                                            <td>
                                                                                                <?php echo $vv['name']?>
                                                                                                <a href="/adminspurview/edit/<?php echo $vv['id']?>" title="编辑"><img src="<?php echo $domain['static']['url'];?>/admin/images/icon_edit.gif" /></a>
                                                                                                <a href="/adminspurview/del/<?php echo $vv['id']?>" title="删除"><img src="<?php echo $domain['static']['url'];?>/admin/images/icon_drop.gif" /></a>
                                                                                            </td>
                                                                                        <?php }}?>
                                                                                    </tr>
                                                                                </table>
                                                                            </td>
                                                                        <?php }?>

                                                                    </tr>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                    <?php } ?>
                                                <?php } ?>

                                            </table>
                                        </td>

                                    </tr>
                                <?php } }?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>

    
<!-- 加载尾部公用js -->
<?php $this->load->view("common/footer");?>

<!-- 底部 -->
<?php $this->load->view("common/bottom");?>

