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
                        <a href="#">网络资源管理</a>
                    </li>

                </ul>
                
            </div>

            <div class="page-content">
                <div class="page-header">
                    <a href="/networkset/add" class="btn btn-sm btn-primary"><i class="fa fa-plus-square" aria-hidden="true"></i> 添加位置</a>
                	<!-- <a href="javascript:void(0);" onclick="goTab();" class="btn btn-sm btn-primary"><i class="fa fa-page" aria-hidden="true"></i> tab管理</a> -->
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
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 投放位置 </label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="name" value="<?php echo $name;?>"  class="col-xs-10 col-sm-12" />
                                                </div>
                                            </div>
                                            
                                            <div class="col-sm-4">
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 所属大类</label>
                                                <div class="col-sm-9">
                                                    <select class="col-xs-10 col-sm-12" name="mod">
                                                    	<?php if(isset($modInfo)) {?>
	                                                    	<?php foreach($modInfo as $k=>$v){?>
	                                                    		<option value="<?php echo $v['id'];?>" <?php if($mod == $v['id']){?>selected<?php }?>><?php echo $v['name'];?></option>
	                                                    	<?php }?>
                                                    	<?php }?>
                                                    </select>
                                                </div>
                                            </div>
                                            
                                        </div>
                                        <div class="clearfix form-actions">
                                            <div class="col-md-offset-3 col-md-9">
                                                <button class="btn btn-sm btn-info" type="submit">
                                                    <i class="fa fa-search"></i>
                                                    查询
                                                </button>
                                                <button class="btn btn-sm" type="reset">
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
                                                <th>投放位置</th>
                                                <th>所属tab</th>
                                                <th>广告形式</th>
                                                <th>格式</th>
                                                <th>单价</th>
                                                <th>总价</th>
                                                <th>折扣</th>
                                                <th>净价</th>
                                                <th>排序</th>
                                                <th>操作</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        if($list){
                                            foreach($list as $key=>$val){
                                                ?>
                                                <tr>
                                                    <td><?php echo $key+1;?></td>
                                                    <td><?php echo $val['name'];?></td>
                                                    <td>
                                                    	<?php if(isset($nettype)) {?>
                                                    		<?php foreach($nettype as $k => $v) {?>
                                                    			<?php if($v['id'] == $val['type']) {echo $v['name'];}?>
                                                    		<?php }?>
                                                    	<?php }?>
                                                    </td>
                                                    <td><?php echo $val['adform'];?></td>
                                                    <td><?php echo $val['format'];?></td>
                                                    <td><?php echo $val['unitprice'];?></td>
                                                    <td><?php echo $val['totalprice'];?></td>
                                                    <td><?php echo $val['discount'];?></td>
                                                    <td><?php echo $val['netprice'];?></td>
                                                    <td><?php echo $val['sort'];?></td>

                                                    <td>
                                                        <div class="visible-md visible-lg hidden-sm hidden-xs action-buttons">
                                                            <a class="green tooltip-info" href="/networkset/edit/<?php echo $val['id'];?>" data-rel="tooltip" data-placement="top" data-original-title="修改">
                                                                <i class="icon-pencil bigger-130"></i>
                                                            </a>
                                                           <a class="red tooltip-info m-del" onclick="del(<?php echo $val['id'];?>);"  href="javascript:;" data-rel="tooltip"  data-placement="top" data-original-title="删除">
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
<script>
function del(id) {
	//alert();

	layer.confirm('您确定删除吗？', {
		  btn: ['确定','取消'] //按钮
		}, function(){
			location.href = 'networkset/del/'+id;
		});
}
</script>
<!-- 加载尾部公用js -->
<?php $this->load->view("common/footer");?>
<script src="<?php echo css_js_url('select2.min.js','admin');?>"></script>
<script type="text/javascript">
                                                
    $(function(){
       $(".select2").css('width','230px').select2({allowClear:true});
    });

    function goTab() {
    	layer.open({
    		  type: 2,
    		  title: 'tab管理',
    		  shadeClose: true,
    		  shade: 0.5,
    		  area: ['800px', '600px'],
    		  content: '/networktype/index' //iframe的url
    		}); 
    }

    
</script>
<!-- 底部 -->
<?php $this->load->view("common/bottom");?>