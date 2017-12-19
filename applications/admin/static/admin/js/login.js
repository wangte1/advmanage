$(function(){
    $('#myModal').on('shown.bs.modal', function () {
        $('#myInput').focus()
    })
    $("body").keydown(function(event) {
        if (event.keyCode == "13") {
            $(".loginbtn").click();
        }
    });
    $(".loginbtn").click(function(){

        var loginuser = $(".loginuser").val();
        var loginpwd = $(".loginpwd").val();

        if(loginuser == "" ){
            artDialog("用户名不能为空");
            $(".loginuser").focus();
            return false;
        }
        if(loginpwd == "" ){
            artDialog("密码不能为空");
            $(".loginpwd").focus();
            return false;
        }


        $.ajax( {
            url:'/login/login',
            data: {
                'name':loginuser,
                'password':loginpwd
             },
            type:'POST',
            dataType:'json',
            beforeSend:function(){
                $(".loginbtn").val("登录中...");
            },
            success:function(data) {
                $(".loginbtn").val("登录");
                if(data.code == 2){
                    artDialog(data.msg);
                    $(".loginuser").focus();
                }
                else if(data.code == 3){
                    artDialog(data.msg);
                    $(".loginpwd").focus();
                }
                else if(data.code == 0){
                    //跳转
                    window.location.href="/home";
                }else{
                    artDialog("未知错误");
                    $(".loginuser").focus();
                }

            },
            error : function() {
               artDialog("网络异常，请稍候再试！");
            }
        });
    });
    function artDialog(info){
        var d = dialog({
            title: '信息提示',
            content: info,
            okValue: '确定',
            ok: function () {}
        });
        d.width(200);
        d.showModal();
    }

});
