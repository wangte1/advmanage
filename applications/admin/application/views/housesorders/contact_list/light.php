<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>冷光灯箱广告制作联系单</title>
    <meta name="keywords" content="">
    <meta name="description" content="">

    <style type="text/css">
        html, body {width: 100%; height: 90%; margin: 0; padding: 0;font-family: "Microsoft YaHei","Helvetica Neue","Helvetica","Arial",sans-serif;}
        .content {width: 1000px; margin: 0 auto}
        .title {font-size: 32px; font-weight: bolder;margin-top: 10px;clear: both}
        .detail-info {width: 1000px; border-collapse: collapse;border-spacing: 0;margin-top: 30px; border: 1px solid black;}
        .detail-info tr td {border: 1px solid black;height: 50px;font-size: 18px;padding-left: 20px;}
        .detail-info .info-item {width: 200px;text-align: center;padding-left: 0; font-weight: bold; font-size: 22px;}
        .detail-info .right-content {font-size: 19px;}
        .detail-info .border-line {border-bottom: 1px solid black;}

        .images-content{ position: absolute; z-index: 1; left: 0; bottom:0; width: 100%; height: 106px; overflow: hidden; } 
        .images-wrapper{ height:170px;  position:relative;} 
        .images-wrapper img {width: 172px; height:169px; margin-left: 248px;}

        .header {padding:10px;}
        .header .logo {width:167px; float: left}
        .header .contact-text {height: 60px; border-left: 3px solid #A7C0DE; float: left; padding-left: 20px; color: #A7C0DE; font-weight: bolder}
        .header p {height: 12px;}
        .footer {margin-top: 10px; padding: 10px; clear: both; color:#D2D2D2}
        .footer p{height: 10px;}
        p{height: 20px}
        @media print { .noprint{display:none;}}
    </style>
</head>
<body>
    <div class="content">
        <?php $this->load->view('housesorders/common/header');?>
        <center class="title">冷光灯箱广告制作联系单</center>
        <table class="detail-info">
            <tbody>
                <tr>
                    <td class="info-item" width="200">客户单位</td>
                    <td class="right-content" width="390"><?php echo $info['customer_name'];?><?php if(isset($info['is_change_pic'])) { echo '<span style="color:red; font-weight: bolder">换画</span>'; } ?></td>
                    <td class="info-item" width="200">下单时间</td>
                    <td class="right-content"><?php echo date('Y-m-d',strtotime($info['create_time']));?></td>
                </tr>
                <tr>
                    <td class="info-item">投放时间</td>
                    <td class="right-content"><?php echo $info['release_start_time']."至".$info['release_end_time'];?></td>
                    <td class="info-item">广告性质</td>
                    <td class="right-content"><?php echo $info['adv_nature'];?></td>
                </tr>
                <tr>
                    <td class="info-item">制作要求</td>
                    <td class="right-content"><?php echo $info['make_requirement'];?></td>
                    <td class="info-item">广告小样</td>
                    <td class="right-content"><?php echo $info['is_sample'] ? '是（'.$info['sample_color'].'）' : '否';?></td>
                </tr>
                <tr>
                    <td class="info-item">制作费用</td>
                    <td colspan="3" class="right-content">
                        <?php echo $info['make_fee'];?>
                    </td>
                </tr>
                <tr>
                    <td class="info-item">备注</td>
                    <td colspan="3" class="right-content">
                        <p style="color: red; font-weight: bolder"><?php echo $info['remark'];?></span>
                    </td>
                </tr>
                <tr>
                    <td class="info-item" style="height: 200px">制作数量</td>
                    <td colspan="3" class="right-content">
                        <?php foreach($make_num as $value):?>
                        <p><?php echo $value['size'];?>：<span class="border-line"><?php echo $value['counts'];?>套（<?php echo $value['make_num'];?>张）</span></p>
                        <?php endforeach;?>
                        <p style="color: red; font-weight: bolder">画面必须高度清晰、平整，规格及内容正确无误。否则，我方有权按照协议做出相关处理</p>
                    </td>
                </tr>
                <tr style="height: 70px">
                    <td class="info-item">业务员签字</td>
                    <td></td>
                    <td class="info-item">媒介专员签字</td>
                    <td></td>
                </tr>
                <tr style="height: 70px">
                    <td class="info-item">总经理签字</td>
                    <td></td>
                    <td class="info-item">财务审核</td>
                    <td></td>
                </tr>
                <tr>
                    <td class="info-item" colspan="4" style="text-align:left; padding-left: 10px">
                        <p>委托单位签章：<span class="border-line">贵阳大视传媒有限公司</span></p>
                        <p><?php echo date('Y年m月d日',strtotime($info['create_time']));?></p>
                        <!-- <p>
                            委托内容：<input type="checkbox" disabled/> 仅制作 
                            <?php if($info['leave_content'] == 1):?><span style="color: red">√</span><?php endif;?>
                            &nbsp;&nbsp;&nbsp;&nbsp; 
                            <input type="checkbox" disabled/> 制作及安装
                            <?php if($info['leave_content'] == 2):?><span style="color: red">√</span><?php endif;?>
                        </p> -->
                    </td>
                </tr>
                <tr>
                    <td class="info-item" colspan="4" style="text-align:left; padding-left: 10px">
                        <p>制作单位：<span class="border-line"><?php echo $info['make_company'];?></span></p>
                        <p>
                            完成时间：<?php echo date('Y年m月d日H时',strtotime($info['make_complete_time']));?> 
                            <span style="margin-left: 200px">完成数量：<?php echo $total_counts;?>套（<?php echo $total_num;?>张）</span>
                        </p>
                        <p>(下单时间起24小时内完成)</p>
                    </td>
                </tr>
                <tr>
                    <td class="info-item" colspan="4" style="text-align:left; padding-left: 10px">
                        <div class="images-wrapper"> 
                            <?php if($info['seal_img']):?>
                            <img src="<?php echo $info['seal_img'];?>" /> 
                            <?php endif;?>
                            <div class="images-content"> 
                                制作单位确认签章：<?php echo $info['make_company'];?>
                            </div> 
                        </div> 
                    </td>
                </tr>
            </tbody>
        </table>
        <?php $this->load->view('orders/common/footer');?>
        <center class="noprint" style="margin: 30px"><button type="button" onclick="javascript: window.print();">打印</button></center>
    </div>
</body>
</html>
