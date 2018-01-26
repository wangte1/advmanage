$(function(){
    $(".select2").css('width','220px').select2({allowClear:true});

    $('#timepicker1').timepicker({
        minuteStep: 1,
        showSeconds: true,
        showMeridian: false,
        defaultTime: '18:00:00'
    }).next().on(ace.click_event, function(){
        $(this).prev().focus();
    });

    //根据客户查询客户名下的所有项目
    $('select[name="customer_id"]').click(function(){
        $.post('/orders/get_project_lists', {'customer_id': $(this).val()}, function(data) {
            if (data.flag) {
                $('select[name="project_id"]').empty();
                $('select[name="project_id"]').append(data.option);
            }
        });

        if ($("#is_lock").val() == 1) {
            searchPoints();
        }
    });

    //根据媒体查询投放点位列表
    $('#media_id').change(function(){
        searchPoints();
    });

    //点击预定订单复选框
    $('#is_lock').change(function(){
        searchPoints();
    });

    //选择点位
    $('#points_lists').on('click', '.do-sel', function(){
        $(this).parent().parent().appendTo($("#selected_points"));
        $("#selected_points_num").html(Number($("#selected_points_num").text()) + 1);  

        if (order_type == 1) {
            var numObj = $(this).parent().parent().find('td:eq(3)');
            var inputVal = numObj.children().val();
            numObj.text(inputVal);
            $("input[name='point_ids']").after('<input type="hidden" name="make_num['+$(this).parent().parent().attr('point-id')+']" value="'+inputVal+'">');
        } else if (order_type == 2) {    //高杆一个点位只对应一张
            $("input[name='point_ids']").after('<input type="hidden" name="make_num['+$(this).parent().parent().attr('point-id')+']" value="1">');
        }
        
        $("#selected_points button").html('移除<i class="fa fa-remove" aria-hidden="true"></i>');
        var point_ids = $("input[name='point_ids']").val() ? $("input[name='point_ids']").val() + ',' + $(this).parent().parent().attr('point-id') :  $(this).parent().parent().attr('point-id');
        $("input[name='point_ids']").val(point_ids);
    });

    //移除点位
    $('#selected_points').on('click', '.do-sel', function(){
        $(this).parent().parent().appendTo($("#points_lists"));
        $("#selected_points_num").html(Number($("#selected_points_num").html()) - 1);

        if (order_type == 1 || order_type == 2) {
            if (order_type == 1) {
                var numObj = $(this).parent().parent().find('td:eq(3)');
                numObj.html('<input type="text" style="width:91px" value="'+numObj.text()+'">');
            }
            $('input[name="make_num['+$(this).parent().parent().attr('point-id')+']"]').remove();
        }

        $("#points_lists button").html('选择<i class="icon-arrow-right icon-on-right"></i>');

        var point_ids = [];
        var _self = $(this);
        $("#selected_points tr").each(function(){
            point_ids.push($(this).attr('point-id'));
        });
        var ids = point_ids.length >= 1 ? point_ids.join(',') : '';
        $("input[name='point_ids']").val(ids);
    });

    //选择全部
    $(".select-all").click(function(){
        $("#points_lists tr").each(function(){
            $(this).appendTo($("#selected_points"));
            $("#selected_points_num").html(Number($("#selected_points_num").text()) + 1);  

            if (order_type == 1) {
                var numObj = $(this).find('td:eq(3)');
                var inputVal = numObj.children().val();
                numObj.text(inputVal);
                $("input[name='point_ids']").after('<input type="hidden" name="make_num['+$(this).attr('point-id')+']" value="'+inputVal+'">');
            } else if (order_type == 2) {
                $("input[name='point_ids']").after('<input type="hidden" name="make_num['+$(this).attr('point-id')+']" value="1">');
            }

            $("#selected_points button").html('移除<i class="fa fa-remove" aria-hidden="true"></i>');
            var point_ids = $("input[name='point_ids']").val() ? $("input[name='point_ids']").val() + ',' + $(this).attr('point-id') :  $(this).attr('point-id');
            $("input[name='point_ids']").val(point_ids);
        });
    });

    //移除全部
    $(".remove-all").click(function(){
        $("#selected_points tr").each(function(){
            $(this).appendTo($("#points_lists"));
            $("#selected_points_num").html('0');

            if (order_type == 1 || order_type == 2) {
                if (order_type == 1) {
                    var numObj = $(this).find('td:eq(3)');
                    numObj.html('<input type="text" style="width:91px" value="'+numObj.text()+'">');
                }
                $('input[name="make_num['+$(this).attr('point-id')+']"]').remove();
            }

            $("input[name='point_ids']").val('');
            $("#points_lists button").html('选择<i class="icon-arrow-right icon-on-right"></i>');
        });
    });

    //是否打小样
    $("input[name='is_sample']").change(function(){
        if($(this).val() == 0) {
            $("input[name='sample_color']").val("");
            $("input[name='sample_color']").hide(); 
            // $("input[name='sample_color']").removeAttr('required');
        } else{
            // $("input[name='sample_color']").attr('required', true);
            $("input[name='sample_color']").show();
        }
    });

    //面数切换
    $('.make-counts').change(function(){
        $("#points_lists tr").find('td:eq(3)').children().val($(this).val());
    });

    //保存
    $(".btn-save").click(function(){
        var point_ids = $("input[name='point_ids']").val();
        if (point_ids == '') {
            var d = dialog({
                title: '提示信息',
                content: '您还没有选择点位哦！',
                okValue: '确定',
                ok: function () {

                }
            });
            d.width(320);
            d.showModal();
            return false;
        }
    });


    function searchPoints() {
        var params = { media_type: $("input[name='order_type']").val(), is_lock: $("#is_lock").val() };
        if ($("#is_lock").val() == 1) params['lock_customer_id'] = $("select[name='customer_id']").val();
        if($("#media_id").val()) params['media_id'] = $("#media_id").val();

        $.post('/orders/get_points', params, function(data){
            $("#points_lists").empty();
            if (data.flag && data.points_lists.length > 0) {
                var lists = data.points_lists;
                var html = '';
                for (var i = 0; i < lists.length; i++) {
                    html += '<tr point-id="'+lists[i]['id']+'">';
                    html += '<td class="col-sm-2">' + lists[i]['points_code'] + '</td>';
                    html += '<td class="col-sm-3">' + lists[i]['media_name'] + '(' + lists[i]['media_code'] + ')' + '</td>';
                    html += '<td class="col-sm-3">' + lists[i]['size'] + '（' + lists[i]['specification_name'] + '）' + '</td>';
                    
                    if (order_type == 1) {
                        var num = $(".make-counts").val() ? $(".make-counts").val() : '2';
                        html += '<td class="col-sm-2"><input type="text" style="width:91px" value="'+num+'" /></td>';
                    }

                    var point_id = lists[i]['id'];
                    var point_ids_arr = $('input[name="point_ids"]').val().split(',');
                    if ($.inArray(point_id, point_ids_arr) != -1) {
                        html += '<td class="col-sm-2"><button class="btn btn-xs btn-default do-sel" type="button" data-id="'+lists[i]['id']+'" disabled>已选择</button></td>';
                    } else {
                        html += '<td class="col-sm-2"><button class="btn btn-xs btn-info do-sel" type="button" data-id="'+lists[i]['id']+'">选择<i class="icon-arrow-right icon-on-right"></i></button></td>';
                    }
                }
                $("#all_points_num").html(data.count);
                $("#points_lists").append(html);
            } 
        });
    }

});