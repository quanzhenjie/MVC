var homeFileLoadPath = (function(script,i,me){
                var l = script.length;
                for( ; i < l; i++ ){
		          me = !!document.querySelector ?
		          script[i].src : script[i].getAttribute('src',4);
		          if( me.substr(me.lastIndexOf('/')).indexOf("home") !== -1 )
		          break;
                }
                me = me.split('?'); _args = me[1];
                return me[0].substr( 0, me[0].lastIndexOf('/') + 1 ) ;
               })(document.getElementsByTagName('script'),0);
var ThemesPath = homeFileLoadPath.replace("scripts/","");//结尾带/
//页面加载完成后执行函数
$(document).ready(function(){
  //鼠标经过改变背景
  $(".menu div").mouseover(function(){
    $(this).css("background","url("+ThemesPath+"images/mouseover_bg.png)");
  });
  //鼠标移出取消背景
  $(".menu div").mouseout(function(){
    $(this).css("background","transparent");
  });
  $("#debug").hide();
});

/*******用户管理********/
function users_manage(){
    $.dialog({id: "users",title: "<img class='icontitle' src='"+ThemesPath+"images/users_min.png' />用户管理",content: "url:index.php?controller=users&action=index",width:800,height:450,button:[{name:"返回列表",callback: function(){$("iframe[name='users']").get(0).contentWindow.location.href="index.php?controller=users&action=index";return false;}},{name:"刷新",callback: function(){$("iframe[name='users']").get(0).contentWindow.location.reload();return false;}},{name:"关闭"}]});
}
