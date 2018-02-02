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
                        <a href="#">订单管理</a>
                    </li>
                    <li>
                        <a href="/confirm_reserve/index">预定订单列表</a>
                    </li>
                    <li class="active">预定订单详情</li>
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
                    <h1>预定订单详情</h1>
                </div>

                <div class="row">
                   <div class="col-xs-12">
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="tabbable">

                                    <div class="tab-content">
                                        
                                        <!-- 客户确认 -->
                                        <div id="customer_confrim" class="tab-pane in active">
                                        	<a href="javascript:;" class="btn btn-xs btn-info btn-export" data-id="<?php echo $info['id'];?>" data-type="<?php echo $info['order_type'];?>" style="margin-bottom:10px">
                                                <i class="fa fa-download out_excel" aria-hidden="true"></i> 导出点位
                                            </a>
                                            <?php if($info['is_confirm'] == 0):?>
                                            <a href="javascript:;" class="btn btn-xs btn-info sign" data-id="<?php echo $info['id'];?>" style="margin-bottom:10px">
                                                <i class="fa fa-check-square-o" aria-hidden="true"></i> 客户签字
                                            </a>
                                            <?php else :?>
                                            <a href="javascript:;" class="btn btn-xs btn-info signed" data="<?php echo $info['confirm_img']?>" style="margin-bottom:10px">
                                                <i class="fa fa-check-square-o"></i> 已签字(查看合同)
                                            </a>
                                            <?php endif;?>
                                        	<table class="table table-striped table-bordered">
                                                <thead>
                                                    <tr>
                                                    	<th width="80px">全选/反选</th>
                                                        <th class="center">行政区域</th>
                                                        <th>楼盘名称</th>
                                                        <th>锁定点位数</th>
                                                        <th>确认点位数</th>
                                                        <th>操作</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach($houses_list as $val):?>
                                                    <tr id="houses_<?php echo $val['id']?>">
                                                    	<td id="all" style="text-align: center;">
                                                    		<input class="all" data-houses_id="<?php echo $val['id']?>" <?php if($val['num'] == $val['confirm_num']){echo 'checked';}?> type="checkbox" />
                                                    	</td>
                                                        <td class="center">
                                                        	<?php echo $val['province'];?>-<?php echo $val['city'];?>-<?php echo $val['area'];?>
                                                        </td>
                                                        <td><?php echo $val['name']?></td>
                                                        <td><?php echo $val['num']?></td>
                                                        <td><?php echo $val['confirm_num']?></td>
                                                        <td>
                                                        	<a style="cursor: pointer;" class="green tooltip-info show_detial" data="<?php echo $val['id']?>" data-name="<?php echo $val['name']?>" data-rel="tooltip" data-placement="top" data-original-title="详情">
                                                                <i class="icon-eye-open bigger-130"></i>
                                                            </a> 
                                                        </td>
                                                    </tr>
                                                    <?php endforeach;?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
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
    //导出预定点位
    $(".btn-export").click(function(){
        var id = $(this).attr('data-id');
        var type = $(this).attr('data-type');
        window.location.href = '/housesscheduledorders/export/' + id + '/' + type;
    });

    $('.sign').on('click', function(){
    	var order_id = '<?php echo $info["id"];?>';
    	var url = '/confirm_reserve/sign?order_id='+order_id;
    	window.location.href = url;
    });
    $('.signed').on('click', function(){
    	var img = $(this).attr('data');
    	console.log(img);
    	window.location.href = "<?php echo $domain['admin']['url']?>"+img;
    });
    

    $('.show_detial').on('click', function(){
	    var order_id = '<?php echo $info["id"];?>';
	    var houses_id = $(this).attr('data');
	    var houses_name = $(this).attr('data-name');
	    var url = '/confirm_reserve/houses_detail?order_id='+order_id+'&houses_id='+houses_id+'&houses_name='+houses_name;
	    window.location.href = url;
	});

    $('.all').on('click', function(e){
        
		var order_id = '<?php echo $info["id"];?>';
		var houses_id = $(this).attr('data-houses_id');
		var status = 0;
		//true全选，false反选
		if($(this).prop('checked')){
			status = 1;
		}
		var obj = $(this);
		var tmp;
		$.post('/housesscheduledorders/select_all', {'order_id':order_id, 'houses_id':houses_id, 'status':status}, function(data){
			if(data.code == 1){
				window.location.reload();
			}else{
				if(status){
					tmp = false;
				}else{
					tmp = true;
				}
				obj.prop('checked', tmp);
			}
		});
    });
</script>
<!-- 底部 -->
<?php $this->load->view("common/bottom");?>
