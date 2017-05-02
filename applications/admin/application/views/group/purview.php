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
    <div class="main-container-inner">
        <!-- 左边导航菜单 -->
        <?php $this->load->view('common/left');?>

        <div class="main-content">
            <div class="breadcrumbs" id="breadcrumbs">
                <ul class="breadcrumb">
                    <li>
                        <i class="icon-home home-icon"></i>
                        <a href="/admingroup"> <?php echo $title[0];?></a>
                    </li>

                  <li class="active"> <?php echo $title[1];?></li>
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
                        <?php echo $title[0];?>
                        <small>
                            <i class="icon-double-angle-right"></i>
                            <?php echo $title[1];?>
                        </small>
                        <a  href="adminspurview/add" style="float: right; margin-right: 50px" class="btn btn-sm btn-primary">添加</a>
                    </h1>
                </div>

                <div class="row">
                    <div class="col-xs-12">
                        <table class="tablelist">
                            <thead>
                            <form method="post">
                                <tr>
                                    <th width="10%">项目</th>
                                    <th>
                                        <span style="width: 10%; text-align: left;">权限</span>
                                        <span style="width: 90%">子权限</span>
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
                                                                                    <tr >
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
    
<!-- 加载尾部公用js -->
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
