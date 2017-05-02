/** 
 * 管理员js文件
 * @author: jianming@gz-zc.cn
 */
define(function(require, exports, module){
    window.jQuery = window.$ = require("jquery");
    var base = require('base');

    module.exports = {
        //设置不可用
        setDisabled: function(btn) {
            $(btn).attr("disabled", true).addClass("btn-disable");
        },

        //保存
        save: function() {
            $(".btn").click(function(){
                var password = $.trim($("#password").val());
                var fullname = $.trim($("#fullname").val());
                var name = $.trim($("#name").val());
                var group_id = $.trim($("select[name=group_id]").val());
                var email = $.trim($("#email").val());
                var tel = $.trim($("#tel").val());
                var disabled =  $('input[name="disabled"]:disabled ').val();
                var describe = $.trim($("#describe").val());
                $.ajax( {
                    url:'/admin/add',
                    data: {
                        'name': name,
                        'group_id':group_id,
                        'password':password,
                        'fullname':fullname,
                        'email':email,
                        'tel':tel,
                        'describe':describe,
                        'disabled':disabled
                    },
                    type:'POST',
                    dataType:'json',
                    success:function(data) {
                        if(data.code == 0){
                            setTimeout(function(){
                                window.location.href="/admin";
                            },2000);

                        }
                        base.comfirmModal(data.msg, false);
                    },
                    error : function() {
                       base.comfirmModal("网络异常", false);
                    }
                });
            });
        },

        //校验密码
        confirmPwd: function() {
            $("#confirpassword").blur(function(){
                if($.trim($("#confirpassword").val()) != $.trim($("#password").val())){
                    $("#confirpassword-msg").html("两次输入的密码不统一");
                    $("#token2").val(0);
                }
                else{
                    $("#confirpassword-msg").html("*");
                    $("#token2").val(1);
                }
            });
        },

        checkFullname: function() {
            $("#fullname").keyup(function(){
                 check();
            });   
        },

        //检查登录名是否存在
        checkAdmin: function() {
            $("#name").keyup(function(){
                $.ajax( {
                    url:'/admin/check_admin',
                    data: {
                        'name': $.trim($("#name").val())
                     },
                    type:'POST',
                    dataType:'json',
                    success:function(data) {
                        if(data.code == 0){
                            $("#name-msg").html("该登录名已经存在");
                        }else{
                            $("#name-msg").html("");
                        }
                        $("#token").val(data.code);
                        check();
                    },
                    error : function() {
                        alert("网络异常！");
                    }
                });
            });
        },

        
    }

    function check(){
        var token = $.trim($("#token").val());
        var token2 = $.trim($("#token2").val());
        var password = $.trim($("#password").val());
        var confirpassword = $.trim($("#confirpassword").val());
        var fullname = $.trim($("#fullname").val());
        var name = $.trim($("#name").val());
        if(token !=0 && token2 != 0 && fullname != "" && confirpassword != "" && password != "" && name != ""){
            $(".btn").attr("disabled", false).removeClass("btn-disable");
        }
        else{
            $(".btn").attr("disabled", true).addClass("btn-disable");
        }
    }
     
});