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
                        <span>组团管理</span>
                    </li>

                </ul>
                <div class="nav-search" id="nav-search">
                </div>
            </div>

            <div class="page-content">
                <div class="page-header">
                    <a href="/housesarea/add" class="btn btn-sm btn-primary"><i class="fa fa-plus-square" aria-hidden="true"></i> 添加组团</a>
                	<a href="javascript:;" class="btn btn-sm btn-primary btn-export"><i class="fa fa-download out_excel" aria-hidden="true"></i> 导出</a>
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
                                    <form id="search-form" class="form-horizontal" role="form">
                                        <div class="form-group">
                                            <div class="col-sm-4">
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 组团名称</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="name" value="<?php echo $name;?>"  class="col-xs-10 col-sm-12" />
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 所属楼盘 </label>
                                                <div class="col-sm-9">
                                                	
                                                	<select class="select2" data-placeholder="Click to Choose..." name="houses_id">
                                                		<option value="all">全部</option>
				                                    	<?php foreach ($list1 as $k => $v) {?>
				                                    		<option value="<?php echo $v['id'];?>" <?php if($v['id'] == $houses_id) {?>selected="selected"<?php }?>><?php echo $v['name'];?></option>
				                                    	<?php }?>
				                                    </select>
                                                </div>
                                            </div>
                                            
                                            <div class="col-sm-4">
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 等级</label>
                                                <div class="col-sm-9">
                                                    <select id="state" name="grade"  class="select2" data-placeholder="Click to Choose...">
                                                        <option value="all" <?php if($grade == 'all' || empty($grade)){ echo 'selected'; }?>>全部</option>
                                                        <?php foreach(C('public.area_grade') as $key=>$val){ ?>
                                                            <option value="<?php echo $key;?>" <?php if($grade != 'all' && ($key == $grade)) { echo "selected"; }?>><?php echo $val;?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                         
                                        	<div class="col-sm-4">
                                        	 	<br/>
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 置业类型</label>
                                                <div class="col-sm-9">
                                                    <select id="state" name="zhiye_id"  class="select2" data-placeholder="Click to Choose...">
                                                        <option value="0">全部</option>
                                                        <?php foreach(C('zhiye') as $key=>$val){ ?>
                                                            <option value="<?php echo $key;?>" <?php if(isset($zhiye_id) && $zhiye_id == $key){echo 'selected';}?> ><?php echo $val;?></option>
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
                                                <th>序号</th>
                                                <th>组团名称</th>
                                                <th>所属楼盘</th>
                                                <th>等级</th>
                                                <!-- <th>坐标</th-->
                                                <th>置业类型</th>
                                                <th>门禁点位数</th>
                                                <th>地面电梯前室点位数</th>
                                                <th>地下电梯前室点位数</th>
                                                <th>合计点位数</th>
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
                                                    <td><a href=""><?php echo $key+1;?></a></td>
                                                    <td><a href=""><?php echo $val['name'];?></a></td>
                                                    <td>
                                                    	<?php echo $houses_name[$val['id']]?>
                                                    </td>
                                                    <td><?php if(isset($area_grade[$val['grade']])) echo $area_grade[$val['grade']];?></td>
                                                    <td>
                                                    	<?php foreach (C('zhiye') as $k => $v):?>
                                                    	<?php if($k == $val['zhiye_id']){echo $v;}?>
                                                    	<?php endforeach;?>
                                                    </td>
													<td><?php if($val['count_1']['count']) echo $val['count_1']['count'];?></td>
													<td><?php if($val['count_2']['count']) echo $val['count_2']['count'];?></td>
													<td><?php if($val['count_3']['count']) echo $val['count_3']['count'];?></td>
													<td><?php if($val['count_4']['count']) echo $val['count_4']['count'];?></td>
													<td class="hidden-480">
                                                        <?php echo $val['remarks'];?>
                                                    </td>
                                                    <td>
                                                        <div class="visible-md visible-lg hidden-sm hidden-xs action-buttons">
                                                            <a class="green tooltip-info" href="/housesarea/edit/<?php echo $val['id'];?>" data-rel="tooltip" data-placement="top" data-original-title="修改">
                                                                <i class="icon-pencil bigger-130"></i>
                                                            </a>
                                                           <a class="red tooltip-info del" href="javascript:;" data-url="/housesarea/del/<?php echo $val['id']?>" data-id="<?php echo $val['id'];?>" data-rel="tooltip" data-placement="top" data-original-title="删除">
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

       	$(".btn-export").click(function(){
        	$("#search-form").attr('action', '/housesarea/out_excel');
            $("#search-form").submit();
            $("#search-form").attr('action', '');
       });
    });
</script>
<!-- 底部 -->
<?php $this->load->view("common/bottom");?>