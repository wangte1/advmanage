<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>公交站台灯箱广告制作联系单</title>
    <meta name="keywords" content="">
    <meta name="description" content="">

    <style type="text/css">
        html, body {width: 100%; height: 90%; margin: 0; padding: 0;}
        .content {width: 1000px; margin: 0 auto}
        .title {font-size: 32px; font-weight: bolder; margin-top: 60px}
        .detail-info {width: 1000px; border-collapse: collapse;border-spacing: 0;margin-top: 40px; border: 1px solid black;}
        .detail-info .info-title {text-align: center;font-size: 20px;font-weight: bold;padding-left: 0;}
        .detail-info tr td {border: 1px solid black;height: 40px;font-size: 14px;padding-left: 20px;}
        .detail-info .info-item {width: 200px;text-align: center;padding-left: 0; font-weight: bold; font-size: 22px;}
        .detail-info .border-line {border-bottom: 1px solid black;}
        @media print { .noprint{display:none;}}
    </style>
</head>
<body>
    <div class="content">
        <center class="title">公交站台灯箱广告制作联系单</center>
        <table class="detail-info">
            <tbody>
                <tr>
                    <td class="info-item" width="200">客户单位</td>
                    <td style="width:300px;"><?php echo $info['customer_name'];?></td>
                    <td class="info-item" width="200">下单时间</td>
                    <td><?php echo $info['create_time'];?></td>
                </tr>
                <tr>
                    <td class="info-item">投放时间</td>
                    <td><?php echo $info['release_start_time']."至".$info['release_end_time'];?></td>
                    <td class="info-item">广告性质</td>
                    <td><?php echo $info['adv_nature'];?></td>
                </tr>
                <tr>
                    <td class="info-item">制作要求</td>
                    <td><?php echo $info['make_requirement'];?></td>
                    <td class="info-item">广告小样</td>
                    <td><?php echo $info['is_sample'] ? '是（'.$info['sample_color'].'）' : '否';?></td>
                </tr>
                <tr>
                    <td class="info-item" style="height: 200px">制作数量</td>
                    <td colspan="3" style="font-size:18px; font-weight: bolder">
                        <p>3.66M*1.50M：<span class="border-line">12套（24张）</span></p>
                        <p>3.16M*1.50M：<span class="border-line">28套（56张）</span></p>
                        <p style="color: red">画面必须高度清晰、平整，规格及内容正确无误。否则，我方有权按照协议做出相关处理</p>
                    </td>
                </tr>
                <tr>
                    <td class="info-item">业务员签字</td>
                    <td></td>
                    <td class="info-item">媒介专员签字</td>
                    <td></td>
                </tr>
                <tr>
                    <td class="info-item">总经理签字</td>
                    <td></td>
                    <td class="info-item">财务审核</td>
                    <td></td>
                </tr>
                <tr>
                    <td class="info-item" colspan="4" style="text-align:left; padding-left: 10px">
                        <p>委托单位盖章：<span class="border-line">贵阳大视传媒有限公司</span></p>
                        <p>2016年7月25日</p>
                        <p>委托内容：<input type="checkbox" /> 仅制作 &nbsp;&nbsp;&nbsp;&nbsp; <input type="checkbox" /> 制作及安装</p>
                    </td>
                </tr>
                <tr>
                    <td class="info-item" colspan="4" style="text-align:left; padding-left: 10px">
                        <p>制作单位：<span class="border-line">贵阳大视传媒有限公司</span></p>
                        <p>完成时间：2016.07.25  <span style="margin-left: 200px">完成数量：40套（80张）</span></p>
                        <p>(下单时间起24小时内完成)</p>
                    </td>
                </tr>
                <tr>
                    <td class="info-item" colspan="4" style="text-align:left; padding-left: 10px">
                        <p>制作单位确认签章：<span class="border-line">贵阳大视传媒有限公司</span></p>
                    </td>
                </tr>
            </tbody>
        </table>
        <center class="noprint" style="margin-top: 50px"><button type="button" onclick="javascript: window.print();">打印</button></center>
    </div>
</body>
</html>
