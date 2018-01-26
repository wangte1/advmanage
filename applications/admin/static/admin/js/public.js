$(function(){
    $('table th input:checkbox').on('click' , function(){
        var that = this;
        $(this).closest('table').find('tr > td:first-child input:checkbox')
            .each(function(){
                this.checked = that.checked;
                $(this).closest('tr').toggleClass('selected');
            });

    });

    $("#serach_shuaixuan").click(function(){
        $(".widget-box-suaxuan").slideToggle();
    });

    $("#cancel,#sub_serach").click(function(){
        $(".widget-box-suaxuan").slideUp();
    });


    //获取对应的经纬度
    $("#coordinate").focus(function(){
        var address_info = "";

        //判断用户是否选择城市和地区
       if($("#state2").val() == ""){
            $("#tishi").slideDown().find(".middle").html("请选择城市!");
            return false;
       }
        if($("#state3").val() == ""){
            $("#tishi").slideDown().find(".middle").html("请选择地区!");
            return false;
        }

        for(var i=0;i<3;i++){
            address_info += $("#address-info").find(".select2-chosen").eq(i).html()+"-";
        }
        $("#tishi").slideUp()
        $.post("/points/get_points",{address_info:address_info,address:$("#address").val()},function(result){

            if(result.errorno == 0){
                $("#coordinate").val(result.lng+","+result.lat);
            }

        });
    });

    //判断点位的唯一性
    $("#points_code").keyup(function(){
        $.post("/points/get_unique_pointscode",{media_id: $("select[name='media_id']").val(), points_code: $(this).val()},function(result){
            if(result.status == 0){
                $("#points_code").css("border-color","red");
                $("#points_code_msg").css("color","red").html("编号已经存在,请确保编号唯一性！");
                $("#points_code_type").val(1);
            }else{
                $("#points_code").removeAttr('style');
                $("#points_code_msg").html("");
                $("#points_code_type").val(0);
            }

        });
    });


    //新增点位
    $("#add").click(function(){
        $("#specifications-name").focus();
        var _self = $(this);
        var type = $("#type").val();
        var name = $("#specifications-name").val();
        var size = $("#specifications-size").val();
        if(name == ""){
           $("#type").css("border-color","red").focus();
           return false;
        }
        if(name == ""){
           $("#specifications-name").css("border-color","red").focus();
           return false;
        }
        if(size == ""){
            $("#specifications-size").css("border-color","red").focus();
            return false;
        }

        $.ajax( {
            url:'/points/add_specifications',
            data: {
                'type':type,
                'name':name,
                'size':size
            },
            type:'POST',
            dataType:'json',
            beforeSend:function(){
                _self.val("添加.....");
            },
            success:function(data) {
                if(data.status == 0){
                    html = '<option selected value="'+data.data.id+'">'+name+'('+size+')</option>';
                   $(".specification-group").find(".select2-chosen").html(name+"("+size+")");
                    $("#guige").append(html);
                    $("#exampleModal").modal('hide');
                }

            },
            error : function() {
                artDialog("网络异常，请稍候再试！");
            }
        });
    });


    //锁定点位
    $(".btn-lock").click(function(){
        $("#point-id").val($(this).attr("data-id"));
        $("#exampleModal").modal('show');
        var _parent = $(this);
        //锁定点位
        $("#lock-add").click(function(){
            var _self = $(this);
            var id = $("#point-id").val();
            var customer_id = $("#c-name").val();
            var lock_start_time = $(".lock_start_time").val();
            var lock_end_time = $(".lock_end_time").val();

            $.ajax( {
                url:'/points/lock_point',
                data: {
                    'id':id,
                    'lock_start_time':lock_start_time,
                    'lock_end_time':lock_end_time,
                    'customer_id':customer_id
                },
                type:'POST',
                dataType:'json',
                beforeSend:function(){
                    _self.val("锁定....");
                },
                success:function(data) {
                   if(data.status == 0){
                       var select_name = $(".lock_select").find("option:selected").attr("data-name");

                       _parent.parents("tr").find(".kehu_name").html(select_name);
                       $("#exampleModal").modal('hide');
                       _parent.parents("tr").find(".btn-lock").hide();
                       _parent.parents("tr").find(".checkbox").attr("data-status","2");
                       _parent.parents("tr").find(".ponit_status").addClass("label-success").removeClass("label-warning").html("预定");
                       _parent.parents("tr").find(".td-lock_end_time").html(lock_end_time);


                    }else{
                        $("#info-msg").html("锁定失败");
                    }

                },
                error : function() {
                    artDialog("信息提示","网络异常，请稍候再试！");
                }
            });
        });


    });


    //删除点位
    $(".potints_del").click(function(){
        var _self = $(this);
        var url = _self.attr("data-url");
        var id = _self.attr("data-id");
        var getUrl = "/points/get_orders_count";
        var failInfo = "该点位下面还占用订单请删除订单再删除点位！";
        delShowDialog("删除提示","你确定要删除该点位？",failInfo,getUrl,url,id);

    });

    //删除媒体
    $(".del").click(function(){
        var _self = $(this);
        var url = _self.attr("data-url");
        var id = _self.attr("data-id");
        var getUrl = "/mediamanage/get_points_nums";
        var failInfo = "请先删除该媒体对应的点位，再删除媒体！";
        delShowDialog("删除提示","你确定删除该媒体？",failInfo,getUrl,url,id);
    });

    //删除客户
    $(".customer-del").click(function(){
        var _self = $(this);
        var url = _self.attr("data-url");
        var id = _self.attr("data-id");
        var getUrl = "/customers/get_customer";
        var failInfo = "该客户正在和我们合作，不能删除!";
        delShowDialog("删除提示","删除之后该客户下面的所有项目也将删除，您确定删除该客户？",failInfo,getUrl,url,id);
    });


    /**
     * 普通提示信息
     *
     * @param msg
     * @param title
     * @param url
     */
    function showDialog(msg, title, url){

        var title = arguments[1] ? arguments[1] : '提示信息';
        var url = arguments[2] ? arguments[2] : '';
        var d = dialog({
            title: title,
            content: msg,
            okValue: '确定',
            ok: function () {
                if(url != '')
                {
                    window.location.href=url;
                }
                return true;
            }
        });
        d.showModal();
    }


    function artDialog(title,info){
        var d = dialog({
            title: title,
            content: info,
            okValue: '确定',
            ok: function () {}
        });
        d.showModal();
    }


    /**
     * 确认提示框
     * @content
     * @url 
     */
    function showConfirm(content, url) {
        var url = arguments[2] ? arguments[2] : '';
        var d = dialog({
            title: '提示信息',
            content: content,
            okValue: '确定',
            ok: function () {
                if(url != '') {
                    window.location.href=url;
                }
                return true;
            },
            cancelValue: '取消',
            cancel: function () {}
        });
        d.width(320);
        d.showModal();
    }



    /**
     * 删除提示框
     * @title 提示标题
     * @content 提示的内容
     * @failinfo 操作失败的提示信息
     * @getUrl 判断是能删除的url
     * @url 删除的跳转连接
     * @id 删除的ID
     */


    function delShowDialog(title,content,failinfo,getUrl,url,id){
        var d = dialog({
            title: title,
            content: content,
            okValue: '确定',
            ok: function () {
                $.ajax( {
                    url:getUrl,
                    data: {
                        'id':id
                    },
                    type:'POST',
                    dataType:'json',
                    beforeSend:function(){

                    },
                    success:function(data) {
                        if(data.status == 0){ //可以删除
                            window.location.href=url;
                        }else{
                            artDialog("信息提示",failinfo);
                        }

                    },
                    error : function() {
                        artDialog("信息提示","网络异常，请稍候再试！");
                    }
                });
            },
            cancelValue: '取消',
            cancel: function () {}
        });
        d.showModal();
        d.width(320);
    }
})