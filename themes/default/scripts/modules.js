/*******列表内所有函数**********/
function order_field_change(){
    if(!$("#order_field").val()){
        window.location.href = window.location.href.replace(/&order=.+?(asc|desc)/ig,"");
    }else{
        $("#order_asc").attr("disabled",false);
        $("#order_desc").attr("disabled",false);
    }
}
function order(type){
    window.location.href = window.location.href.replace(/&order=.+?(asc|desc)/ig,"&order="+$("#order_field").val()+" "+type)+(url_get("order")?"":"&order="+$("#order_field").val()+" "+type);
}
function search(){
    if($("#search_keyword").val().length<1){
        window.location.href = window.location.href.replace(/&where=[^&]*|&page=\d+/ig,"");
    }else{
        window.location.href = window.location.href.replace(/&where=[^&]*|&page=\d+/ig,"")+"&where="+$("#search_field").val()+" like %27%25"+$("#search_keyword").val().replace("#","%23")+"%25%27";
    }
}
function press_enter(e,doc){
    if(e.keyCode == 13 && doc.id == "search_keyword"){
        search();
    }
    if(e.keyCode == 13 && doc.id == "goto_page"){
        search();
    }
}
function open_advanced_search(){
    if($("#advanced_search_form_container").css("display")=="none"){
        $("#advanced_search_form_container").show();
    }else{
        $("#advanced_search_form_container").hide();
    }
}
var advanced_search_tmp_var = {
    history_value : null,
    input_id : null
}
function number_check(e,doc){
    if((e.keyCode>=48 && e.keyCode<=57) || (e.keyCode>=96 && e.keyCode<=105) || e.keyCode==190 || e.keyCode==110 || e.keyCode==8){
        return true;
    }else{
        advanced_search_tmp_var.history_value = doc.value.replace(/[^\d]/ig,"");
        advanced_search_tmp_var.input_id = doc.id;
        setTimeout("document.getElementById(advanced_search_tmp_var.input_id).value=advanced_search_tmp_var.history_value;",100);
        return false;
    }
}
function all_select(doc){
    if(doc.checked){
        $("input[name='default_id']").attr("checked",true);
    }else{
        $("input[name='default_id']").removeAttr("checked");
    }
}
function advanced_search(){
    var advanced_search_where = "&where=";
    var advanced_search_inputs = $("#advanced_search_form_container input:text");
    for(var i=0;i<advanced_search_inputs.length;i++){
        advanced_search_where += i?" and ":"";
        if(advanced_search_inputs[i].id.replace(/_(start|end)$/ig,"") == advanced_search_inputs[i].name){
            if(advanced_search_inputs[i].name+"_start" == advanced_search_inputs[i].id){
                advanced_search_where += advanced_search_inputs[i].name+" between %27"+advanced_search_inputs[i].value+"%27";
            }else{
                advanced_search_where += "%27"+(advanced_search_inputs[i].value.length?advanced_search_inputs[i].value:"3000-01-01")+"%27";
            }
        }else{
            advanced_search_where += advanced_search_inputs[i].id+" like %27%25"+advanced_search_inputs[i].value+"%25%27";
        }
    }
    window.location.href = window.location.href.replace(/&where=[^&]*|&page=\d+/ig,"")+advanced_search_where.replace("#","%23");
}
function add(){
    window.location.href = window.location.href.replace(/\?.*$/ig,"")+"?controller="+url_get("controller")+"&action=add&backURL="+escape(url_get("backURL")?url_get("backURL"):window.location.href);
}
function view(id){
    if(id){
        window.location.href = window.location.href.replace(/\?.*$/ig,"")+"?controller="+url_get("controller")+"&action=view&id="+id+"&backURL="+escape(window.location.href);
    }
}
function single_delete(id){
    if(confirm("确定删除该项数据吗？")){
        $.post(window.location.href.replace(/\?.*$/ig,"")+"?controller="+url_get("controller")+"&action=delete",{"where":"id = '"+id+"'"},function(back_data){
                show_debug(back_data.debug);
                logout(back_data.login);
                if(back_data.result){
                    alert("删除"+back_data.result+"条记录");
                    window.location.reload();
                }else{
                    alert("删除失败");
                }
            },"json");
    }
}
function multiple_delete(){
    if(confirm("确定删除所有选中项数据吗？")){
        var input_checked = $("input[name='default_id']:checked");
        if(input_checked.length<1){
            alert("未选中任何项");
            return false;
        }
        var where = "id = '"+input_checked[0].value+"'";
        for(var i=1;i<input_checked.length;i++){
            where += " or id = '"+input_checked[i].value+"'";
        }
        $.post(window.location.href.replace(/\?.*$/ig,"")+"?controller="+url_get("controller")+"&action=delete",{"where":where},function(back_data){
                show_debug(back_data.debug);
                logout(back_data.login);
                if(back_data.result){
                    alert("删除"+back_data.result+"条记录");
                    window.location.reload();
                }else{
                    alert("删除失败");
                }
            },"json");
    }
}
function excel_table(){
    window.location.href = window.location.href.replace(/(&action=)(\w+?)(&|$)/ig,"$1excel$3");
}

function back_list(){
    window.location.href = url_get("backURL");
}
/***分页函数****/
function gotopage(maxpage){
    if($("#goto_page").val()==0 || $("#goto_page").val()>maxpage || !/^\d+?$/.exec($("#goto_page").val())){
        alert("必须为有效的数字");
        return false;
    }
    window.location.href = window.location.href.replace(/&page=\d+/ig,"&page="+$("#goto_page").val())+(url_get("page")>0?"":"&page="+$("#goto_page").val());
}
function firstpage(){
    window.location.href = window.location.href.replace(/&page=\d+/ig,"");
}
function prevpage(){
    window.location.href = window.location.href.replace(/&page=\d+/ig,"&page="+(url_get("page")-1>1?url_get("page")-1:1));
}
function nextpage(maxpage){
    window.location.href = window.location.href.replace(/&page=\d+/ig,"&page="+(Number(url_get("page"))+1>maxpage?maxpage:Number(url_get("page"))+1))+(url_get("page")>0?"":"&page="+(2>maxpage?maxpage:2));
}
function endpage(maxpage){
    window.location.href = window.location.href.replace(/&page=\d+/ig,"&page="+maxpage)+(url_get("page")>0?"":"&page="+maxpage);
}
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
/******未登录跳转*******/
function logout(login){
    if(!login){
        window.location.href = window.location.href.replace(/\?.*$/ig,"")+"?controller=login&action=index&redirectURL="+escape(window.location.href);
    }
}