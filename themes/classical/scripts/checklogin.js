//页面加载完成后执行函数
$(document).ready(function(){
    var input_value = {
        user_value : false,
        pwd_value : false
    }
    var input_function = {
        pwd_focus : function(){
            $("#pwd").focus(function(){
                $(this).remove();
                $("#pwd_label").after('<input type="password" id="pwd" name="pwd" class="pwd_input" title="请输入密码" />');
                $("#pwd").focus();$("#pwd").focus();
                $("#pwd").blur(function(){
                    if($("#pwd").val().length<1 || !/^[\w]+?$/.exec($("#pwd").val())){
                        $(this).remove();
                        $("#pwd_label").after('<input type="text" id="pwd" name="pwd" class="pwd_input" title="请输入密码" />');
                        $("#pwd").css("color","#999999");
                        $("#pwd").val("请输入密码");
                        input_function.pwd_focus();
                        input_value.pwd_value = false;
                    }
                });
                $("#pwd").keypress(function(e){
                    if(e.which == 13){
                        input_function.login();
                    }
                });
                input_value.pwd_value = true;
            });
        },
        login : function(){
            if(!input_value.user_value || $("#user").val().length<1 || !/^[\w]+?$/.exec($("#user").val())){
                $.dialog.alert('请输入用户名。',function(){});
                return false;
            }
            if(!input_value.pwd_value || $("#pwd").val().length<1){
                $.dialog.alert('请输入密码。',function(){});
                return false;
            }
            //$.dialog.tips('数据处理中...请稍后',5,'loading.gif');
            $.post(window.location.href.replace(/\?.*$/ig,"")+"?controller=login&action=login",{"username":$("#user").val(),"password":$("#pwd").val()},function(back_data){
                show_debug(back_data.debug);
                if(Number(back_data.result)>0){
                    if(url_get('redirectURL')){
                        window.location.href = url_get('redirectURL');
                    }else{
                        window.location.href = window.location.href.replace(/\?.*$/ig,"")+"?controller=home&action=index";
                    }
                }else{
                    $.dialog.alert('用户名或密码错误。',function(){});
                }
            },"json");
        }
    }
    if($("#user").val().length<1 || !/^[\w]+?$/.exec($("#user").val())){
        $("#user").css("color","#999999");
        $("#user").val("请输入用户名");
        input_value.user_value = false;
    }else{
        $("#user").css("color","#333333");
        input_value.user_value = true;
    }
    if($("#pwd").val().length<1 || !/^[\w]+?$/.exec($("#pwd").val())){
        $("#pwd").css("color","#999999");
        $("#pwd").val("请输入密码");
        input_value.pwd_value = false;
    }
    $("#user").focus(function(){
        if(!input_value.user_value){
            $("#user").css("color","#333333");
            $("#user").val("");
        }
        input_value.user_value = true;
    });
    $("#user").blur(function(){
        if($("#user").val().length<1 || !/^[\w]+?$/.exec($("#user").val())){
            $("#user").css("color","#999999");
            $("#user").val("请输入用户名");
            input_value.user_value = false;
        }
    });
    input_function.pwd_focus();
    //注册提交按钮
    $("#submit_button").click(function(){
        input_function.login();
    });
});
/*******获取GET值**********/
function url_get(name) {
    name = name.replace(/[\[]/, "\\\[").replace(/[\]]/, "\\\]");
    var regexS = "[\\?&]" + name + "=([^&#]*)";
    var regex = new RegExp(regexS);
    var results = regex.exec(window.location.href);
    if (results == null) {
        return "";
    } else {
        return decodeURIComponent(results[1].replace(/\+/g, " "));
    }
}
/*******显示debug***********/
function show_debug(debug){
    try{
        var debug_list_number = $(".debug_list").length;
        for(var i=0;i<debug.length;i++){
            $("#debug").append('<div class="debug_list"><table><tr><th rowspan="2" class="debug_id">'+(debug_list_number+i+1)+'</th><td class="debug_sql">'+debug[i].sql+'</td></tr><tr><td class="debug_note">'+debug[i].note+'</td></tr></table></div>');
        }
    }catch(evt){
        ;
    }
}
