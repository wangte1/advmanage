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
            							<?php 
            							    $i = 0;
            							    $j = 0;
            							    if($list){
                							    foreach ($list as $k => $v){
                							        $i += $v['select_num'];
                							        $j += $v['num'];
                							    }
                							    if(($j == $i) && ($i > 0)){
                							        echo 'checked';
                							    }
            							    }
            							?>
            							class="select_page_all" 
            							type="checkbox" />
            					</th>
            					<th class="col-sm-3">
            						<select id="area_id" name="area_id" style="width:100%;" class="select">
            							<option value="0">组团</option>
            							<?php if(isset($list)):?>
            							<?php $area_list = array_column($list, 'area_name', 'area_id');?>
            							<?php foreach (array_unique(array_column($list, 'area_id')) as $k => $v):?>
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
            					<th class="col-sm-3">
            						<select id="ban" name="ban" style="width:100%;" class="select">
            							<option value="0">楼栋</option>
            							<?php if(isset($list)):?>
            							<?php foreach (array_unique(array_column($list, 'ban')) as $k => $v):?>
            							<?php if(!empty($v)):?>
            							<option 
            								<?php if(isset($ban)&& ($v == $ban)){echo 'selected';}?>
            								value="<?php echo $v;?>"><?php echo $v;?></option>
            							<?php endif;?>
            							<?php endforeach;?>
            							<?php endif;?>
            						</select>
            					</th>
            					<th>点位总数</th>
            				</tr>
            			</thead>
            			<tbody>
            				<?php foreach($list as $k => $v) {?>
            				<tr>
            					<td>
            						<input class="ban" <?php if($v['num'] == $v['select_num']){echo 'checked';}?> data-ban="<?php echo $v['ban']?>" data-area_id="<?php echo $v['area_id']?>" type="checkbox" />
            					</td>
            					<td><?php echo $v['area_name']?></td>
            					<td><?php echo $v['ban'];?></td>
            					<td><?php echo $v['num']?></td>
            				</tr>
            				<?php }?>
            			</tbody>
            		</table>
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
				'status':status
			},
			function(data){
				console.log(data);return;
    			if(data){
    				window.location.reload();
    			}
		});
	});

	$('.ban').on('click', function(){
		var status = 0;
		var area_id = $(this).attr('data-area_id');
		var ban = $(this).attr('data-ban');
		//true全选，false反选
		if($(this).prop('checked')){
			status = 1;
		}
		$.post(
			'/housesscheduledorders/select_ban',
			{
				'order_id':order_id,
				'ban':ban,
				'area_id':area_id,
				'status':status
			}, 
			function(data){
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
