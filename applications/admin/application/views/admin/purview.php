<!-- 加载公用css -->
<?php $this->load->view('common/header');?>
<link href="<?php echo css_js_url('style.css', 'admin');?>" rel="stylesheet" />
<style>
    td{ border: 1px solid #EDF6FA}
    #son-child td{ border: 0px; text-align: left; padding-left: 5px;}
    #son td{ text-align: left; padding: 0px 10px;}
</style>

<!-- 头部 -->
<?php $this->load->view('common/top');?>

<div class="main-container" id="main-container">
    <script type="text/javascript">
        try{ace.settings.check('main-container' , 'fixed')}catch(e){}
    </script>

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
                        <a href="#">管理员管理</a>
                    </li>
                    <li class="active">分配管理员权限</li>
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
                        分配权限
                    </h1>
                </div>

                <div class="row">
                    <div class="col-xs-12">

                        <table class="tablelist">
                            <thead>
                            <form method="post">
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
                                    <tr class="check_body">
                                        <td>
                                            <input type="checkbox" name="purview[]" onclick="selectAll(this,'.1_<?php echo $val['id'];?>');" value="<?php echo $val['id'];?>" <?php if(in_array($val['id'],$purview_ids)){ echo 'checked="true"';}?>/>
                                            <?php echo $val['name'];?>
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
                                                                            <input class="1_<?php echo $val['id'];?>" type="checkbox" name="purview[]" onclick="selectAll(this,'.2_<?php echo $v['id'];?>');" value="<?php echo $v['id'];?>"
                                                                                <?php if(in_array($v['id'],$purview_ids)){ echo 'checked="true"';}?>/>
                                                                        </td>
                                                                        <?php  if(@$v['child']){ ?>
                                                                            <td>
                                                                                <table id="son-child">
                                                                                    <tr>
                                                                                        <?php
                                                                                        if(@$v['child']){foreach(@$v['child'] as $kk=>$vv){  ?>
                                                                                            <td>
                                                                                                <?php echo $vv['name']?>
                                                                                                <input class="2_<?php echo $v['id'];?> 1_<?php echo $val['id'];?>" type="checkbox" name="purview[]" value="<?php echo $vv['id'];?>"  <?php if(in_array($vv['id'],$purview_ids)){ echo 'checked="true"';}?> />
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
                            <tr>
                                <td colspan="2" style="text-align: center;">
                                    <input type="checkbox" name="dd" id="all_check">
                                    全选&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" value="确定" class="btn btn-sm btn-primary" />
                                </td>
                            </tr>
                            </form>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view("common/footer");?>
<script type="text/javascript">
    function selectAll(obj,div_id){
        $(div_id).prop("checked", $(obj).prop("checked"));
    }
    
    $("#all_check").click(function(){
        sel(this);
    });

    function sel(obj) {
        if (obj.checked) {
            var attr = $(".check_body").find("input");
            for (var i = 0; i <= attr.length; i++) {
                if (attr[i] != undefined || attr[i] != null)
                    attr[i].checked = true;
            }
        } else {
            var attr = $(".check_body").find("input");
            for (var i = 0; i <= attr.length; i++) {
                if (attr[i] != undefined || attr[i] != null)
                    attr[i].checked = false;
            }

        }
    }

</script>

<!-- 底部 -->
<?php $this->load->view("common/bottom");?>

