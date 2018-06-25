<!-- 重点参数：iconStyle -->
<!doctype html>
<html lang="zh-CN">

<head>
    <base href="http://webapi.amap.com/ui/1.0/ui/overlay/SimpleMarker/examples/" />
    <meta charset="utf-8">
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no, width=device-width">
    <title>工程人员分布图</title>
    <style>
    html,
    body,
    #container {
        width: 100%;
        height: 100%;
        margin: 0px;
    }
    
    #myIcon {
        background: orange;
        width: 20px;
        height: 60px;
        border-radius: 10px;
    }
    </style>
</head>

<body>
    <div id="container"></div>
    <script type="text/javascript" src='http://webapi.amap.com/maps?v=1.4.6&key=9d5dc7871ccbcdf68ec964c2cdd39404'></script>
    <!-- UI组件库 1.0 -->
    <script src="http://webapi.amap.com/ui/1.0/main.js?v=1.0.11"></script>
    <script type="text/javascript">
    //创建地图
    var map = new AMap.Map('container', {
        zoom: 12
    });
    map.clearMap();  // 清除地图覆盖物
    var markers = [
        <?php if($list):?>
        <?php foreach ($list as $key => $val):?>
        {
            icon: 'http://cdn.wesogou.com/p32.png',
            position: [<?php echo floatval($val['longitude']);?>, <?php echo floatval($val['latitude']);?>],
            // 设置label标签
            label:{
                //label默认蓝框白底左上角显示，样式className为：amap-marker-label
                offset: new AMap.Pixel(25, -5),
                //修改label相对于maker的位置
                content: "<?php echo 'ID:'.$val['user_id'].' '.$val['fullname']?>"
            }
    	}, 
    	<?php endforeach;?>
    	<?php endif;?>
    ];
    
    markers.forEach(function(marker) {
        new AMap.Marker({
            map: map,
            icon: marker.icon,
            position: [marker.position[0], marker.position[1]],
            offset: new AMap.Pixel(-16, -32),
            label:{
                //label默认蓝框白底左上角显示，样式className为：amap-marker-label
                offset: marker.label.offset,
                //修改label相对于maker的位置
                content: marker.label.content,
            }
        });
    });
	
    



    
    </script>
</body>

</html>