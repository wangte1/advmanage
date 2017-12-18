<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>LED广告投放联系单</title>
    <meta name="keywords" content="">
    <meta name="description" content="">

    <style type="text/css">
        html, body {width: 100%; height: 90%; margin: 0; padding: 0;font-family: "Microsoft YaHei","Helvetica Neue","Helvetica","Arial",sans-serif;}
        .content {width: 1000px; margin: 0 auto}
        .title {font-size: 32px; font-weight: bolder; margin-top: 10px;clear: both}
        .detail-info {width: 1000px; height:1098px; border-collapse: collapse;border-spacing: 0;margin-top: 30px; border: 1px solid black;}
        .detail-info .info-title {text-align: center;font-size: 20px;font-weight: bold;padding-left: 0;}
        .detail-info tr td {border: 1px solid black;height: 50px;font-size: 18px;padding-left: 20px;}
        .detail-info .info-item {width: 200px;text-align: center;padding-left: 0; font-weight: bold; font-size: 22px;}
        .detail-info .right-content {font-size:18px; }
        .detail-info .border-line {border-bottom: 1px solid black;}

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
        <?php $this->load->view('orders/common/header_nocontact');?>
        <center class="title">LED广告投放联系单</center>
        <table class="detail-info">
            <tbody>
                <tr>
                    <td class="info-item" width="200">客户单位</td>
                    <td class="right-content" width="390"><?php echo $info['customer_name'];?><?php if($info['project_name']) { echo '-'.$info['project_name']; } ?><?php if(isset($info['is_change_pic'])) { echo '<span style="color:red; font-weight: bolder">换画</span>'; } ?></td>
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
                    <td class="info-item">广告频次</td>
                    <td class="right-content"><?php echo $info['adv_frequency'];?></td>
                </tr>
                <tr>
                    <td class="info-item">备注</td>
                    <td colspan="3" class="right-content">
                        <p><?php echo $info['remark'];?></span>
                    </td>
                </tr>
                <tr>
                    <td class="info-item">广告位置</td>
                    <td colspan="3" class="right-content" style="text-align:center">
                        <?php echo $info['order_type'] == 3 ? '机场LED' : '元和国际LED';?>
                    </td>
                </tr>
                <tr>
                    <td class="info-item">广告画面</td>
                    <td colspan="3" class="right-content" style="padding: 5px 0 0 0; text-align: center">
                        <?php 
                            if ($info['order_type'] == 3) {
                                switch (count($adv_img)) {
                                    case '2':
                                        $width = '660px';
                                        $height = '220px';
                                        break;
                                    case '3':
                                        $width = '510px';
                                        $height = '170px';
                                        break;
                                    default:
                                        $width = '780px';
                                        $height = '260px';
                                        break;
                                }
                            } else {
                                switch (count($adv_img)) {
                                    case '2':
                                        $width = '390px';
                                        $height = '260px';
                                        break;
                                    case '3':
                                        $width = '360px';
                                        $height = '240px';
                                        break;
                                    default:
                                        $width = '660px';
                                        $height = '440px';
                                        break;
                                }
                            }
                        ?>
                        <?php foreach($adv_img as $key => $value): ?>
                            <?php if($info['order_type'] == 3):?>
                            <img src="<?php echo $value;?>" style="width: <?php echo $width;?>; height: <?php echo $height;?>" />
                            <?php else:?>
                            <span><img src="<?php echo $value;?>" style="width: <?php echo $width;?>; height: <?php echo $height;?>" /></span>
                            <?php if($key == 0 && count($adv_img) == 3):?><br/><?php endif;?>
                            <?php endif;?>
                        <?php endforeach;?>
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
                <tr style="height: 200px">
                    <td class="info-item" colspan="4" style="text-align:left; padding-left: 10px">
                        <p>单位签章：<span class="border-line">贵阳大视传媒有限公司</span></p>
                        <p><?php echo date('Y年m月d日',strtotime($info['create_time']));?></p>
                    </td>
                </tr>
            </tbody>
        </table>
        <?php $this->load->view('orders/common/footer');?>
        <center class="noprint" style="margin: 30px"><button type="button" onclick="javascript: window.print();">打印</button></center>
    </div>
</body>
</html>
