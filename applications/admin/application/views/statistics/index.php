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
                        <a href="#">数据统计</a>
                    </li>
                    <li class="active">订单统计</li>
                </ul>


            </div>

            <div class="page-content">
                <div class="page-header">

                </div>

                <div class="row">
                    <div class="col-xs-12">
                        <div class="widget-box collapsed">
                            <div class="widget-header">
                                <h4>筛选</h4>
                                <div class="widget-toolbar">
                                    <a href="#" data-action="collapse">
                                        <i class="icon-chevron-down"></i>
                                    </a>
                                </div>
                            </div>

                            <div class="widget-body">
                                <div class="widget-body-inner" style="display: none;">
                                    <div class="widget-main">

                                        <form class="form-horizontal" role="form">
                                            <div class="form-group">
                                                <div class="col-sm-3">
                                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 年份 </label>
                                                    <div class="col-sm-9">
                                                        <select name="y" class="select2">
                                                            <?php foreach($years as $value):?>
                                                                <option value="<?php echo $value;?>" <?php if($value == $y){ echo "selected"; }?>><?php echo $value;?></option>
                                                            <?php endforeach;?>
                                                        </select>
                                                    </div>
                                                </div>


                                                <div class="col-sm-3">
                                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 订单类型 </label>
                                                    <div class="col-sm-9">
                                                        <select name="type" class="select2">
                                                            <option value=""></option>
                                                            <?php foreach($media_type as $key=>$value):?>
                                                                <option value="<?php echo $key;?>" <?php if($key == $order_type){ echo "selected"; }?>><?php echo $value;?></option>
                                                            <?php endforeach;?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 全部业务人员 </label>
                                                    <div class="col-sm-9">
                                                        <select name="sales" class="select2">
                                                            <option value="">全部业务员</option>
                                                            <?php foreach($salesman as $key=>$value):?>
                                                                <option value="<?php echo $value['id'];?>" <?php if($value['id'] ==$sales){ echo "selected"; }?>><?php echo $value['name'];?></option>
                                                            <?php endforeach;?>
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
                    </div>

                     <div class="col-xs-12" style="padding-bottom: 20px; padding-top: 20px;">

                       <b class="statistics-t">类型: <i style="color: #6CD7D9; font-size: 14px; font-style: normal"><?php echo @$media_type[$order_type]?$media_type[$order_type]:"全部";?></i></b>

                       <b class="statistics-t">总签约金额: <i style="color: #EEB3FF; font-size: 16px; font-style: normal"><?php echo $total_money;?></i> 万</b>
                       <b class="statistics-t">总订单数: <i style="color: #FFD155; font-size: 16px; font-style: normal"><?php echo $total_orders;?></i></b>


                      </div>
                    <div class="col-xs-12">
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="table-responsive" id="echart-count" style="height: 400px;color: #2B7DBC">
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
<script src="<?php echo css_js_url('echarts.js', 'common');?>"></script>
<script src="<?php echo css_js_url('select2.min.js','admin');?>"></script>
<script type="text/javascript">
    var staticBath = "<?php echo $domain['static']['url']?>";
    require.config({
        paths: {
            echarts: staticBath+"/common/js"
        }
    });
</script>
<script type="text/javascript">
    $(".select2").css('width','240px').select2({allowClear:true});
    require(
        [
            'echarts',
            'echarts/chart/line',
            'echarts/chart/bar'
        ],
        function (ec) {
            var myChart = ec.init(document.getElementById('echart-count'), 'macarons');
            option = {
                title : {
                    text : '<?php echo $y;?>年订单统计情况'
                },
                tooltip : {
                    trigger: 'axis'
                },
                toolbox: {
                    show : true,
                    feature : {
                        dataView: {show: true, readOnly: false},
                        magicType: {show: true, type: ['line', 'bar']},
                        restore: {show: true},
                        saveAsImage: {show: true}
                    }
                },
                calculable : true,
                legend: {
                    data:['销售额（万元）','订单数量']
                },
                xAxis : [
                    {
                        type : 'category',
                        data : ['1月','2月','3月','4月','5月','6月','7月','8月','9月','10月','11月','12月']
                    }
                ],
                yAxis : [
                    {
                        type : 'value',
                        name : '销售额（万元）',
                        axisLabel : {
                            formatter: '{value} 万'
                        }
                    },
                    {
                        type : 'value',
                        name : '订单数量',
                        axisLabel : {
                            formatter: '{value} 单'
                        }
                    }
                ],
                series : [
                    {
                        name:'销售额（万元）',
                        type:'bar',
                        data:<?php echo $money_data;?>,
                        markLine : {
                            data : [
                                {type : 'average', name: '平均值'}
                            ]
                        }
                    },
                    {
                        name:'订单数量',
                        type:'line',
                        yAxisIndex: 1,
                        data:<?php echo $orders_data;?>,
                        markLine : {
                            data : [
                                {type : 'average', name: '平均值'}
                            ]
                        }
                    }
                ]
            };
            myChart.setOption(option);
        }
    );
</script>

<!-- 底部 -->
<?php $this->load->view("common/bottom");?>
