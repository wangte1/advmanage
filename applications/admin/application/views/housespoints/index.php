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
                        <a href="#">社区资源管理</a>
                    </li>
                    
                    <li>
                        <span>点位管理</span>
                    </li>

                </ul>
                <div class="nav-search" id="nav-search">
                </div>
            </div>

            <div class="page-content">
                <div class="page-header">
                    <a href="/housespoints/add" class="btn btn-sm btn-primary"><i class="fa fa-plus-square" aria-hidden="true"></i> 新增点位</a>
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
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 点位类型 </label>
                                                <div class="col-sm-9">
                                                	<select class="select2" data-placeholder="Click to Choose..." name="type_id">
                                                		<option value="">全部</option>
				                                    	<?php foreach ($tlist as $k => $v) {?>
				                                    		<option value="<?php echo $v['type'];?>" <?php if($v['type'] == $type_id) {?>selected="selected"<?php }?>><?php echo $order_type_text[$v['type']];?></option>
				                                    	<?php }?>
				                                    </select>
                                                </div>
                                            </div>
                                            
                                            <div class="col-sm-4">
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 所属楼盘 </label>
                                                <div class="col-sm-9">
                                                	<select class="select2" data-placeholder="Click to Choose..." name="houses_id">
                                                		<option value="">全部</option>
				                                    	<?php foreach ($hlist as $k => $v) {?>
				                                    		<option value="<?php echo $v['id'];?>" <?php if($v['id'] == $houses_id) {?>selected="selected"<?php }?>><?php echo $v['name'];?></option>
				                                    	<?php }?>
				                                    </select>
                                                </div>
                                            </div>
                                            
                                            <div class="col-sm-4">
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 所属楼盘区域 </label>
                                                <div class="col-sm-9">
                                                	<select class="select2" data-placeholder="Click to Choose..." name="area_id">
                                                		<option value="">全部</option>
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
                                                <th>点位编号</th>
                                                <th>所属楼盘</th>
                                                <th>所属楼盘区域</th>
                                                <th>地址</th>
                                                <th>类型</th>
                                                <th>占用客户</th>
                                                <th>点位属性</th>
                                                <th>状态</th>
                                                <th>锁定状态</th>
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
                                                    <td>
                                                    	<?php foreach ($hlist as $k => $v) {?>
                                                    		<?php if($v['id'] == $val['houses_id']) {?>
                                                    			<?php echo $v['name'];break;?>
                                                    		<?php }?>
                                                    	<?php }?>
                                                    </td>
                                                    <td>
                                                    	<?php foreach ($alist as $k => $v) {?>
                                                    		<?php if($v['id'] == $val['area_id']) {?>
                                                    			<?php echo $v['name'];break;?>
                                                    		<?php }?>
                                                    	<?php }?>
                                                    </td>
                                                    <td>
                                                    	<?php echo $val['addr'];?>
                                                    </td>
                                                    <td>
                                                    	<?php foreach ($tlist as $k => $v) {?>
                                                    		<?php if($v['type'] == $val['type_id']) {?>
                                                    			<?php echo $order_type_text[$v['type']];break;?>
                                                    		<?php }?>
                                                    	<?php }?>
                                                    </td>
													<td><?php if(isset($customer_name[$val['customer_id']])) echo $customer_name[$val['customer_id']];?></td>
													<td></td>
													<td>
                                                        <?php 
	                                                        switch ($val['point_status']) {
	                                                            case '1':
	                                                                $class = 'badge-success';
	                                                                break;
	                                                            case '2':
	                                                                $class = 'badge-warning';
	                                                                break;
	                                                            case '3':
	                                                                $class = 'badge-danger';
	                                                                break;
	                                                        }
	                                                    ?>
	                                                    <span class="badge <?php echo $class; ?>">
	                                                        <?php echo C('public.points_status')[$val['point_status']];?>
	                                                    </span>
                                                    </td>
                                                    <td>
                                                        <?php 
	                                                        switch ($val['is_lock']) {
	                                                            case '1':
	                                                                $class = 'badge-warning';
	                                                                break;
	                                                            default:
	                                                                $class = 'badge-success';
	                                                        }
	                                                    ?>
	                                                    <span class="badge <?php echo $class; ?>">
	                                                        <?php echo C('housesscheduledorder.point_status')[$val['is_lock']];?>
	                                                    </span>
                                                    </td>
                                                    <td>
                                                        <div class="visible-md visible-lg hidden-sm hidden-xs action-buttons">
                                                            <a class="green tooltip-info" href="/housespoints/edit/<?php echo $val['id'];?>" data-rel="tooltip" data-placement="top" data-original-title="修改">
                                                                <i class="icon-pencil bigger-130"></i>
                                                            </a>
                                                           <a class="red tooltip-info del" href="javascript:;" data-url="/housespoints/del/<?php echo $val['id']?>" data-id="<?php echo $val['id'];?>" data-rel="tooltip" data-placement="top" data-original-title="删除">
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