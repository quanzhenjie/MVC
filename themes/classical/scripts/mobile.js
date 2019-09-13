/****长按事件执行
    * id        载体id值
    * timeout   长按毫秒数 500
    * run       执行函数名包括参数   例如：run(a,b,c,d,...)
    */
function LongPressRun(id,timeout,run){
    var timeOutEvent = 0;
    //定时器
    //开始按
    document.getElementById(id).addEventListener("touchstart",function(e){
        timeOutEvent = setTimeout(run+";", timeout);
        //这里设置定时器，定义长按N毫秒触发长按事件，时间可以自己改，个人感觉500毫秒非常合适
        return false;
    });
    //如果手指有移动，则取消所有事件，此时说明用户只是要移动而不是长按
    document.getElementById(id).addEventListener("touchmove",function(e){
        clearTimeout(timeOutEvent);
        //清除定时器
        timeOutEvent = 0;
    });
    //手释放，如果在N毫秒内就释放，则取消长按事件，此时可以执行onclick应该执行的事件
    document.getElementById(id).addEventListener("touchend",function(e){
        clearTimeout(timeOutEvent);
        //清除定时器
        if (timeOutEvent != 0) {
            //这里写要执行的内容（尤如onclick事件）
        }
        return false;
    });
}
            
            
            
            
            
            