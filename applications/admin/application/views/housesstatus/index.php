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
                        <a href="#">社区资源管理</a>
                    </li>
                    <li class="active">点位状态</li>
                </ul>

            </div>

            <div class="page-content">
                <div class="page-header">
<!--                     <a href="/housesapp/add" class="btn btn-sm btn-primary"><i class="fa fa-plus-square" aria-hidden="true"></i> 新增版本</a> -->
                </div> 
                
                    <div class="col-xs-12">
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="table-responsive">
                                    <div id="container" style="min-width:400px;height:400px"></div>
                                    <div class="row">
                                        <div class="col-sm-6">
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="dataTables_paginate paging_bootstrap">

                                                <ul class="pagination">
                                                    <?php echo $pagestr;?>
                                                </ul>

                                            </div>
                                        </div>
                                    </div>
                                    <!-- 分页 -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="http://adv.dev.wesogou.com/static/common/js/highcharts.js"></script>
<script src="https://img.hcharts.cn/highcharts/modules/exporting.js"></script>
<script src="http://adv.dev.wesogou.com/static/common/js/highcharts-zh_CN.js"></script>
<script>
		    var chart = Highcharts.chart('container', {
		    title: {
		        text: '点位占用预览'
		    },
		    tooltip: {
		        headerFormat: '{series.name}<br>',
		        pointFormat: '{point.name}: <b>{point.percentage:.1f}%</b>'
		    },
		    plotOptions: {
		        pie: {
		            allowPointSelect: true,  // 可以被选择
		            cursor: 'pointer',       // 鼠标样式
		            dataLabels: {
		                enabled: true,
		                format: '<b>{point.name}</b>: {point.percentage:.1f} %',
		                style: {
		                    color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
		                }
		            }
		        }
		    },
// 		    1空闲，3占用，4报损
		    series: [{
		        type: 'pie',
		        name: '点位统计状态',
		        data: [
		            {
		                name: '空闲',
		                y: <?php echo $count1;?>,
		                sliced: true,  // 默认突出
		                selected: true // 默认选中 
		            },
		            ['占用',    <?php echo $count3?>],
		            ['报损',    <?php echo $count4?>]
		        ]
		    }]
			});
        </script>
<!-- 加载尾部公用js -->
<?php $this->load->view("common/footer");?>

<!-- 底部 -->
<?php $this->load->view("common/bottom");?>
