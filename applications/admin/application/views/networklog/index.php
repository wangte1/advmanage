<!-- 加载公用css -->
<?php $this->load->view('common/header');?>

<!-- 头部 -->
<?php $this->load->view('common/top');?>
<style>
#table th,td{
	 text-align:center; /*设置水平居中*/
     vertical-align:middle;/*设置垂直居中*/
     cursor:pointer
}

/*#table td:hover{
	 background-color: green;
}*/

.font-red {
	color: red;
}
</style>


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
                    <li class="active">网络排班</li>
                </ul>

            </div>
			
            <div class="page-content">
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
                                    <form id="search-form" class="form-horizontal" role="form" method="get" action="">
                                        <div class="form-group" style="padding-bottom: 20px;">
                                        	<div class="col-sm-4">
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 申请人</label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="form-control"  name="apply_user_name" value="<?php if(isset($apply_user_name)) echo $apply_user_name;?>">
                                                </div>
                                            </div>
                                            
                                            <div class="col-sm-4">
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 对应客户</label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="form-control"  name="customer" value="<?php if(isset($customer)) echo $customer;?>">
                                                </div>
                                            </div>
                                        
                                            <!-- <div class="col-sm-4">
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 状态</label>
                                                <div class="col-sm-9">
                                                    <select class="form-control"  name="status">
                                                    	<option></option>
                                                    	<option value="0" <?php if($status == '0') {?>selected="selected"<?php }?>>审核中</option>
                                                    	<option value="1" <?php if($status == 1) {?>selected="selected"<?php }?>>已预定</option>
                                                    	<option value="3" <?php if($status == 3) {?>selected="selected"<?php }?>>已上画</option>
                                                    	<option value="2" <?php if($status == 2) {?>selected="selected"<?php }?>>未通过</option>
                                                    </select>
                                                </div>
                                            </div> -->
                                        </div>
                                        <div class="clearfix form-actions">
                                            <div class="col-md-offset-3 col-md-9">
                                                <button class="btn btn-info" type="submit">
                                                    <i class="fa fa-search"></i>
                                                    查询
                                                </button>
                                                <button class="btn" type="reset" type="button">
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
				                	<table id="table" class="table table-bordered">
					                	<thead>
					                		<tr>
						                		<th>序号</th>
						                		<th>id</th>
						                		<th>操作</th>
						                		<th>操作人</th>
						                		<th>操作时间</th>
						                		<th>操作</th>
					                		</tr>
					                	</thead>
					                	
					                	<tbody>
					                		<?php if(isset($list)) {?>
						                		<?php foreach($list as $key => $val) {?>
						                		<tr>
						                			<td><?php echo $key+1?></td>
						                			<td><?php echo $val['id']?></td>
						                			<td><?php echo $val['apply']?></td>
						                			<td><?php echo $val['apply_user_name']?></td>
						                			<td><?php if($val['apply_time']) {?><?php echo date("Y-m-d H:i:s",$val['apply_time'])?><?php }?></td>
						                			<td>
						                				<button type="button" onclick="detail(<?php echo $val['id']?>);return;" class="btn btn-xs btn-primary">详情</button>
						                			</td>
						                		</tr>
						                		<?php }?>
					                		<?php }?>
					                	</tbody>
					                
					                </table>
                				</div>
               				 </div>
                		</div>
                	</div>
                	</div>
                
                <!-- 分页 -->
                <?php $this->load->view('common/page');?>
            </div>
        </div>
    </div>
</div>


<!-- 加载尾部公用js -->
<?php $this->load->view("common/footer");?>
<!-- 底部 -->
<?php $this->load->view("common/bottom");?>
<script>

function detail(id) {
    layer.open({
      type: 2,
      title: '排班申请详情',
      shadeClose: true,
      shade: false,
      maxmin: true, //开启最大化最小化按钮
      area: ['70%', '600px'],
      content: '/networklog/detail?id='+id
    });
		 
}


</script>
