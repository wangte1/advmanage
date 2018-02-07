/** 
 * 后台公用js文件
 * @author: jianmign@gz-zc.cn
 */
define(function(require, exports, module){
    window.jQuery = window.$ = require("jquery");
    require('kindeditor');
    require('datepicker');
    require('tabs');
    require('dialog');

    module.exports = {
        //弹出框
        comfirmModal: function(content,hasBtn, callback){
            if(hasBtn===false){
                $("#sure").hide();
                $("#cancel").val("我知道了");
                $("#cancel").attr("class","sure");
            }else{
                $("#sure").show();
                $("#cancel").attr("class","cancel");
                $("#cancel").val("取消");

            }
            $(".content").html(content);
            $(".tip").fadeIn(200);

            $(".tiptop a").click(function(){
                $(".tip").fadeOut(200);
            });

            $("#sure").click(function(){
                $(".tip").fadeOut(100);
            });

            $("#cancel").click(function(){
                $(".tip").fadeOut(100);
            });
        },

        //时间控件
        initDatePicker: function(format) {
        	if(!format){
        		format = 'yyyy-MM-dd HH:mm:ss';
        	}
            $(".Wdate").focus(function(){
                WdatePicker({dateFmt: format})
            });
        },

        //初始化tab框
        initTabs: function() {
            $("#usual1 ul").idTabs();
        },

        //dialog弹出框
        showDialog:showDialog,

        //上传文件
        uploadFile: function (show,save,type,save_title){
            KindEditor.ready(function(K) {
                var editor = K.editor({
                    //指定上传文件的服务器端程序。
                    uploadJson : baseUrl + '/File/upload',
                    //true时显示浏览远程服务器按钮。
                    allowFileManager : false
                     
                });
                
                $(show).click(function() {
                    if(type == 'image') {
                        editor.loadPlugin('image', function() {
                            editor.plugin.imageDialog({
                                showRemote : false, //不允许网络图片
                                clickFn : function(url, title, width, height, border, align) {
                                    editor.hideDialog();
                                    if(save) {
                                        K(save).val(url);
                                        K(save).attr('src',url);
                                        K(save).attr('href',url);
                                        K(save_title).attr('src',url);
                                    } else {
                                        window.location.reload();
                                    }
                                }
                            });
                        });
                    }
                    if(type == 'file') {
                        editor.loadPlugin('insertfile', function() {
                            editor.plugin.fileDialog({
                                fileUrl : K('#url').val(),
                                clickFn : function(url, title) {
                                    editor.hideDialog();
                                    if(save){
                                        K(save).val(url);
                                        K(save).attr('src',url);
                                        K(save).attr('href',url);
                                        K(save_title).val(title);
                                    } else {
                                        window.location.reload();
                                    }
                                }
                            });
                        });
                    }
                });
            });
        },

        //编辑器
        reloadEdit: function reloadEdit(content){
            KindEditor.ready(function(K) {
                var editor = K.create(content, {
                    width :'700px',
                    height:'400px',
                    //指定上传文件的服务器端程序。
                    uploadJson : baseUrl + '/File/upload',
                    //true时显示浏览远程服务器按钮。
                    allowFileManager : false,
                    filterMode:false,
                    pasteType: 1,  //自动清除格式
                    items:[
                        'source', '|', 'undo', 'redo', '|', 'preview', 'cut', 'copy', 'paste',
                        'plainpaste',  '|', 'justifyleft', 'justifycenter', 'justifyright',
                        'justifyfull', 'insertorderedlist', 'insertunorderedlist', 'indent', 'outdent', 'clearhtml', 'quickformat', 'selectall', '|', 'fullscreen', '/',
                        'formatblock', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold',
                        'italic', 'underline', 'strikethrough', 'lineheight', 'removeformat', '|', 'image', 'multiimage',
                        'flash', 'media', 'insertfile', 'table', 'hr','anchor', 'link', 'unlink'
                    ],
                    afterBlur: function(){this.sync();}
                    //syncType:'form'
                });

                //设置摘要
                K('#setSummary').click(function(e) {
                    $('#summary').val(Clear(K.formatHtml(editor.selectedHtml(), { 'q' : ['q']})));
                });

            });
        }
    }

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
            },
        });
        d.width(320);
        d.showModal();
    }
     
});