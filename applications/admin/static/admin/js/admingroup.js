/** 
 * 管理员角色js文件
 * @author: jianming@gz-zc.cn
 */
define(function(require, exports, module){
    window.jQuery = window.$ = require("jquery");
    var base = require('base');

    module.exports = {
        //保存
        save: function() {
            $(".btn").click(function(){
                var token = $.trim($("#token").val());
                if(token == "0"){
                    $("#name-msg").html("请重新填写别的角色名");
                    $("#name").focus();
                    return false;
                }
                $.ajax( {
                    url:'/admingroup/add',
                    data: {
                        'name': $.trim($("#name").val()),
                        'describe': $.trim($("#describe").val())
                    },
                    type:'POST',
                    dataType:'json',
                    success:function(data) {
                        if(data.code == 0){
                            alert("添加成功");
                            window.location.href="/admingroup";
                        }else{
                            $("#name-msg").html("添加失败，请重新添加！");
                        }

                    },
                    error : function() {
                        alert("网络异常！");
                    }
                });
            });
        },

        //检测角色名唯一性
        checkGroup: function(){
            $("#name").keyup(function(){
                $.ajax( {
                    url:'/admingroup/check_name',
                    data: {
                        'name': $.trim($("#name").val())
                     },
                    type:'POST',
                    dataType:'json',
                    success:function(data) {
                        if(data.code == 0){
                            $("#name-msg").html("该角色已经存在");
                        }else{
                            $("#name-msg").html("");
                        }
                        $("#token").val(data.code);
                    },
                    error : function() {
                        alert("网络异常！");
                    }
                });
            });
        }
        
    }

});