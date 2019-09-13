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
  
                // Store variables
                var accordion_head = $('.accordion > li.father > a'),
                    accordion_body = $('.accordion li > .sub-menu');
                // Open the first tab on load
                //accordion_head.first().addClass('active').next().slideDown('normal');
                // Click function
                accordion_head.on('click', function(event) {
                    $('.accordion > li.son > a').removeClass('active');
                    // Disable header links
                    event.preventDefault();
                    // Show and hide the tabs on click
                    if ($(this).attr('class') != 'active'){
                        accordion_body.slideUp('normal');
                        $(this).next().stop(true,true).slideToggle('normal');
                        accordion_head.removeClass('active');
                        $(this).addClass('active');
                    }else{
                        accordion_body.slideUp('normal');
                        accordion_head.removeClass('active');
                    }
                });
  
  $("#debug").hide();
});

//页面跳转
function GoToMainPage(doc,link){
    if($(doc).attr('class') != "active"){
        
        if($(doc).parent().attr("class") == "son"){
            $('.accordion > li.father > a.active').removeClass('active');
            $('.accordion li > .sub-menu').each(function(o,i){
                $(this).slideUp('normal');
                $(this).find("a").removeClass('active');
            });
        }else{
            $('.sub-menu a').removeClass('active');
        }
        $(doc).addClass('active');
        
        //内框架页面跳转
        $("#contentpage").attr("src",link);
    }
}
var WindowObject = null;
//弹窗跳转
function GoToWindowPage(width,height,title,link){
    WindowObject = $.dialog({lock:true,title: title,content: "url:"+link,width:width,height:height});
}

//刷新右边iframe
function IframeReload(){
    $('#contentpage').attr('src', $('#contentpage').attr('src'));
}