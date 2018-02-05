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
                        <a href="/housesscheduledorders">预定订单待确认列表</a>
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
            	<div class="tab-content">
                <div class="page-header">
                    <h1>楼盘：<?php echo $houses_name;?></h1>
                    <br/>
                    <p><a href="/confirm_reserve/detail/<?php echo $order_id;?>"><button class="btn btn-warning btn-xs">
						<i class="ace-icon fa fa-reply icon-only"></i>返回
					</button></a>
					</p>
                </div>

                <div class="row">
                	<div id="table-panel">

            	    <table id="sample-table-1" class="table table-striped table-bordered table-hover" >
            			<thead>
            				<tr>
            					<th>
            						<input 
            							class="select_page_all" 
            							<?php if(count($point_list) == $page_confirm_point_num){echo 'checked';}?>
            							value="<?php echo implode(',', array_column($point_list, 'id'));?>"
            							type="checkbox" />
            					</th>
            					<th>点位编号</th>
            					<th class="col-sm-1">
            						<select id="area_id" name="area_id" style="width:100%;" class="select">
            							<option value="0">组团</option>
            							<?php if(isset($point_list)):?>
            							<?php $area_list = array_column($point_list, 'area_name', 'area_id');?>
            							<?php foreach (array_unique(array_column($point_list, 'area_id')) as $k => $v):?>
            							<option 
            								<?php if(isset($area_id) && ($v == $area_id)){echo 'selected';}?>
            								value="<?php echo $v;?>">
            								<?php foreach (array_unique($area_list) as $key => $val):?>
            								<?php if(!empty($val)):?>
            								<?php if($v == $key):?>
            								<?php echo $val;?>
            								<?php endif;?>
            								<?php endif;?>
            								<?php endforeach;?>
            							</option>
            							<?php endforeach;?>
            							<?php endif;?>
            						</select>
            					</th>
            					<th class="col-sm-1">
            						<select id="ban" name="ban" style="width:100%;" class="select">
            							<option value="0">楼栋</option>
            							<?php if(isset($point_list)):?>
            							<?php foreach (array_unique(array_column($point_list, 'ban')) as $k => $v):?>
            							<?php if(!empty($v)):?>
            							<option 
            								<?php if(isset($ban)&& ($v == $ban)){echo 'selected';}?>
            								value="<?php echo $v;?>"><?php echo $v;?></option>
            							<?php endif;?>
            							<?php endforeach;?>
            							<?php endif;?>
            						</select>
            					</th>
            					<th>单元</th>
            					<th>楼层</th>
            					<th>位置</th>
            					<th>点位类型</th>
            				</tr>
            			</thead>
            			<tbody>
            				<?php foreach($point_list as $k => $v) {?>
            				<tr>
            					<td>
            						<input class="point" 
            						data-point_id="<?php echo $v['id']?>"  
            						<?php if(in_array($v['id'], $confirm_point_ids)){echo 'checked';}?>
            						type="checkbox" />
            					</td>
            					<td><?php echo $v['code'];?></td>
            					<td><?php echo $v['area_name']?></td>
            					<td><?php echo $v['ban'];?></td>
            					<td><?php echo $v['unit'];?></td>
            					<td><?php echo $v['floor'];?></td>
            					<td><?php if(isset($point_addr[$v['addr']])) echo $point_addr[$v['addr']];?></td>
            					<td><?php if(isset($order_type_text[$v['type_id']])) echo $order_type_text[$v['type_id']];?></td>
            				</tr>
            				<?php }?>
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

<!-- 加载尾部公用js -->
<?php $this->load->view("common/footer");?>
<script type="text/javascript">

	var order_id = '<?php echo $order_id?>';
	var houses_id = '<?php echo $houses_id?>';
	$('.select_page_all').on('click', function(data){
		var status = 0;
		var point_ids = $(this).val();
		//true全选，false反选
		if($(this).prop('checked')){
			status = 1;
		}
		$.post(
			'/housesscheduledorders/select_page_all',
			{
				'order_id':order_id, 
				'houses_id':houses_id, 
				'point_ids':point_ids,
				'status':status
			},
			function(data){
    			if(data){
    				window.location.reload();
    			}
		});
	});

	$('.point').on('click', function(data){
		var status = 0;
		var point_id = $(this).attr('data-point_id');
		//true全选，false反选
		if($(this).prop('checked')){
			status = 1;
		}
		$.post('/housesscheduledorders/select_one', {'order_id':order_id, 'point_id':point_id, 'status':status}, function(data){
			if(data){
				window.location.reload();
			}
		});
	});
	
	//组团或楼栋筛选
	$('#ban,#area_id').on('change', function(){
		var url  = '/confirm_reserve/houses_detail';
			url += '?order_id=<?php echo $order_id;?>';
			url +='&houses_id=<?php echo $houses_id;?>';
			url +='&houses_name=<?php echo $houses_name;?>';
		var area_id = $('#area_id').val();
		if(area_id != 0){
			url += '&area_id='+area_id; 
		}
		var ban = $('#ban').val();
		if(ban != 0){
			url += '&ban='+ban; 
		}
		window.location.href = url;
	});
</script>
<!-- 底部 -->
<?php $this->load->view("common/bottom");?>
