/*
 *注释：用于压缩，裁剪图片 只能适用于支持HTML5的浏览器 （火狐浏览器目前还有点问题，有待改进，chrome完美使用）
 *作者：全振杰
 *右键取消选区
 */
 
 
 //该插件的配置参数
var CI_options = {
    name : "CutImage",   //默认不需要改，与插件的文件名相同，以字母开头，允许结合数字
    resizable : 2,  //来源图片是否调整尺寸，参数：（0,1,2），0时，默认原图比例和分辨率，工作区域的范围失效，并可继续自定义裁剪，1时，自动按比例调整为不超过工作区域的分辨率，不再继续裁剪，2时，自动按比例调整为不超过工作区域的分辨率，并可继续自定义裁剪
    width : 600,  //工作区域的宽度，图片宽度不超过此宽度，超过自动缩放
    height : 380,  //工作区域的高度，图片宽度不超过此高度，超过自动缩放
    buttons : [
        {name : "上传" ,click : function(){alert("上传");}},
        {name : "自定义" ,click : function(){alert("自定义");}}
        //更多...
    ]//自定义按钮
};

function OpenCI(){
    document.getElementById(CI_options.name).className = document.getElementById(CI_options.name).className + " ci_show";
    document.querySelector(".ci_process").className = document.querySelector(".ci_process").className + " ci_show";
}

function CloseCI(){
    document.getElementById(CI_options.name).className = document.getElementById(CI_options.name).className.replace(" ci_show","");
    document.querySelector(".ci_process").className = document.querySelector(".ci_process").className.replace(" ci_show","");
    CI_var.CanvasObject.clearRect(0, 0, CI_var.Canvas.width, CI_var.Canvas.height);
    document.getElementById(CI_options.name+"_source").value = null;
    CI_var.PreviewImage = new Image();
    CI_var.StartPoint = {};
    CI_var.EndPoint = {};
}

//全局变量 不需要设置
var CI_var = {
    Canvas : {},//画布
    CanvasObject : {},//画布对象
    PreviewImage : new Image(),//预览图
    StartPoint : {},//鼠标起始点
    EndPoint : {},//鼠标结束点
    LoadPath : (function(script,i,me){
                var l = script.length;
                for( ; i < l; i++ ){
		          me = !!document.querySelector ?
		          script[i].src : script[i].getAttribute('src',4);
		          if( me.substr(me.lastIndexOf('/')).indexOf(CI_options.name) !== -1 )
		          break;
                }
                me = me.split('?'); _args = me[1];
                return me[0].substr( 0, me[0].lastIndexOf('/') + 1 ) ;
               })(document.getElementsByTagName('script'),0)//加载本插件的绝对路径
};

//创建工作区域的CSS
function CreateCSS(){
    var head = document.getElementsByTagName('head')[0],
    link = document.createElement('link');
    link.href = CI_var.LoadPath + CI_options.name + '.css';
    link.rel = 'stylesheet';
    head.insertBefore(link, head.firstChild);
}

//创建画布容器 HTML5
function CreateCanvas(){
    if(!document.querySelector("#"+CI_options.name)){
        var div = document.createElement("div");
        div.id = CI_options.name;
        div.style.width = CI_options.width+"px";
        div.className = "ci_main";
        var content = document.createElement("div");
        content.id = CI_options.name+"_content";
        content.className = "ci_content";
        content.style.height = CI_options.height+"px";
        div.appendChild(content);
        var input_file = document.createElement("input");
        input_file.id = CI_options.name+"_source";
        input_file.type = "file";
        input_file.style.display = "none";
        div.appendChild(input_file);
        var div_process = document.createElement("div");
        div_process.className = "ci_process";
        var input_button_array = [];
        for(var index in CI_options.buttons){
            input_button_array[index] = document.createElement("input");
            input_button_array[index].type = "button";
            input_button_array[index].value = CI_options.buttons[index].name;
            input_button_array[index].onclick = CI_options.buttons[index].click;
            div_process.appendChild(input_button_array[index]);
        }
        var input_loadimage = document.createElement("input");
        input_loadimage.type = "button";
        input_loadimage.value = "获取图像";
        input_loadimage.onclick = function(){LoadImage();};
        div_process.appendChild(input_loadimage);
        var input_close = document.createElement("input");
        input_close.type = "button";
        input_close.value = "关闭";
        input_close.onclick = function(){CloseCI();};
        div_process.appendChild(input_close);
        div.appendChild(div_process);
        var canvas = document.createElement("canvas");
        canvas.id = CI_options.name+"_canvas";
        canvas.height = 0;
        content.appendChild(canvas);
        document.body.appendChild(div);
        CI_var.Canvas = canvas;
        CI_var.CanvasObject = canvas.getContext('2d');
        //鼠标右键菜单失效
        canvas.addEventListener('contextmenu', function(e) {
            e.preventDefault();
            e.stopPropagation();
        });
        //添加阴影层
        var overlay = document.createElement("div");
        overlay.id = CI_options.name+"_overlay";
        overlay.className = "ci_overlay";
        document.body.appendChild(overlay);
    }
    return CI_options.name;
}

//监听页面是否完成加载，如完成则初始化CI
function InitCI(){
    if(document.readyState == "complete"){
        CreateCSS();
        CreateCanvas();
        SelectArea();
    }else{
        setTimeout("InitCI()",100);
    }
}
InitCI();

//加载图片到画布 HTML5
function LoadImage(){
    document.getElementById(CI_options.name+"_source").addEventListener('change', source_change);
    document.getElementById(CI_options.name+"_source").click();
    function source_change(){
        var fr = new FileReader();
        fr.onload = function() {
            var temp_base64 = null;
            if(CI_options.resizable > 0){
                var temp_image = new Image();
                temp_image.src = this.result;
                var scale = temp_image.width/CI_options.width>temp_image.height/CI_options.height?temp_image.width/CI_options.width:temp_image.height/CI_options.height;
                if(scale>1){
                    CI_var.Canvas.width = Math.floor(temp_image.width/scale);
                    CI_var.Canvas.height = Math.floor(temp_image.height/scale);
                    CI_var.CanvasObject.drawImage(temp_image, 0, 0,temp_image.width/scale,temp_image.height/scale);
                    temp_base64 = CI_var.Canvas.toDataURL();
                    //document.getElementById(CI_options.name).style.display = "block";
                }else{
                    //document.getElementById(CI_options.name).style.display = "flex";
                }
            }
            CI_var.PreviewImage.src = temp_base64?temp_base64:this.result;
            CI_var.Canvas.width = CI_var.PreviewImage.width;
            CI_var.Canvas.height = CI_var.PreviewImage.height;
            CI_var.CanvasObject.drawImage(CI_var.PreviewImage, 0, 0, CI_var.PreviewImage.width, CI_var.PreviewImage.height); //把图片绘制到canvas上
        };
        fr.readAsDataURL(this.files[0]);
        document.getElementById(CI_options.name+"_source").removeEventListener('change', source_change);
    }
}

//选定裁剪区域 HTML5 鼠标事件下e.button有0,1,2分别代表左键，中键，右键
function SelectArea(){
    //不可裁剪时将全选画布区域
    if(CI_options.resizable == 1){
        CI_var.StartPoint.x = 0;
        CI_var.StartPoint.y = 0;
        CI_var.StartPoint.drag = false;
        CI_var.EndPoint.x = CI_var.Canvas.width;
        CI_var.EndPoint.y = CI_var.Canvas.height;
        return null;
    }
    //确定鼠标起始点
    CI_var.Canvas.addEventListener('mousedown', function(e) {
        if(e.button === 0 && CI_var.StartPoint.x == undefined) {
            CI_var.StartPoint.x = e.offsetX;
            CI_var.StartPoint.y = e.offsetY;
            CI_var.StartPoint.drag = true;
        }
    });
    //鼠标移动的时候不停绘制裁剪区域
    CI_var.Canvas.addEventListener('mousemove', function(e) {
        if(e.button === 0 && CI_var.StartPoint.drag){
            var nPoint = {
                x: e.offsetX,
                y: e.offsetY
            };
            CI_var.CanvasObject.save();    //clip要通过restore回复
            CI_var.CanvasObject.clearRect(0, 0, CI_var.Canvas.width, CI_var.Canvas.height);    //画布全清
            CI_var.CanvasObject.drawImage(CI_var.PreviewImage, 0, 0);    //绘制底图
            DrawCover();    //绘制阴影
            CI_var.CanvasObject.beginPath();    //开始路径
            CI_var.CanvasObject.rect(CI_var.StartPoint.x, CI_var.StartPoint.y, nPoint.x - CI_var.StartPoint.x, nPoint.y - CI_var.StartPoint.y);    //设置路径为选取框
            CI_var.CanvasObject.clip();    //截取路径内为新的作用区域
            CI_var.CanvasObject.drawImage(CI_var.PreviewImage, 0, 0);    //在选取框内绘制底图
            CI_var.CanvasObject.restore();    //恢复clip截取的作用范围
        }
    });
    //最后我们添加松开鼠标的事件监听，松开左键为拖动结束，松开右键为复原
    CI_var.Canvas.addEventListener('mouseup', function(e) {
        if(e.button === 0 && CI_var.EndPoint.x == undefined) {
            CI_var.StartPoint.drag = false;
            CI_var.EndPoint.x = e.offsetX;
            CI_var.EndPoint.y = e.offsetY;
        }else if(e.button === 2) {
            Restore();
        }
    });
    //双击画布将进行裁剪
    CI_var.Canvas.addEventListener('dblclick', function(e) {
        CutImage();
    });
    //画布上绘制阴影
    function DrawCover(){
        CI_var.CanvasObject.save();
        CI_var.CanvasObject.fillStyle = 'rgba(0, 0, 0, 0.3)';
        CI_var.CanvasObject.fillRect(0, 0, CI_var.Canvas.width, CI_var.Canvas.height);
        CI_var.CanvasObject.restore();
    }
    //选定区域重置还原
    function Restore(){
        CI_var.StartPoint = {};
        CI_var.EndPoint = {};
        CI_var.CanvasObject.drawImage(CI_var.PreviewImage, 0, 0);
    }
}
//裁剪
function CutImage(){
    if(CI_var.StartPoint.x !== undefined && CI_var.EndPoint.x !== undefined && (CI_var.StartPoint.x != CI_var.EndPoint.x || CI_var.StartPoint.y != CI_var.EndPoint.y)){
        /* 排除起始点与结束点重叠的情况下，下面一段代码作用相同，写法不同，选择其中一种即可
        CI_var.CanvasObject.clearRect(0, 0, CI_var.Canvas.width, CI_var.Canvas.height);    //清空画布
        CI_var.Canvas.width = Math.abs(CI_var.EndPoint.x - CI_var.StartPoint.x);    //重置canvas的大小为新图的大小
        CI_var.Canvas.height = Math.abs(CI_var.EndPoint.y - CI_var.StartPoint.y);
        CI_var.CanvasObject.drawImage(CI_var.PreviewImage,CI_var.StartPoint.x, CI_var.StartPoint.y, CI_var.EndPoint.x - CI_var.StartPoint.x, CI_var.EndPoint.y - CI_var.StartPoint.y, 0, 0,Math.abs(CI_var.EndPoint.x - CI_var.StartPoint.x),Math.abs(CI_var.EndPoint.y - CI_var.StartPoint.y));
        */
        var imgData = CI_var.CanvasObject.getImageData(CI_var.StartPoint.x, CI_var.StartPoint.y, CI_var.EndPoint.x - CI_var.StartPoint.x, CI_var.EndPoint.y - CI_var.StartPoint.y);    //把裁剪区域的图片信息提取出来
        CI_var.CanvasObject.clearRect(0, 0, CI_var.Canvas.width, CI_var.Canvas.height);    //清空画布
        CI_var.Canvas.width = Math.abs(CI_var.EndPoint.x - CI_var.StartPoint.x);    //重置canvas的大小为新图的大小
        CI_var.Canvas.height = Math.abs(CI_var.EndPoint.y - CI_var.StartPoint.y);
        CI_var.CanvasObject.putImageData(imgData, 0, 0);    //把提取出来的图片信息放进canvas中
        
        CI_var.PreviewImage.src = CI_var.Canvas.toDataURL();    //裁剪后我们用新图替换底图，方便继续处理 toDataURL(mime,quality)
        
        CI_var.StartPoint = {};//选定区域重置还原
        CI_var.EndPoint = {};//选定区域重置还原
        CI_var.CanvasObject.drawImage(CI_var.PreviewImage, 0, 0);//选定区域重置还原
        
        document.getElementById(CI_options.name+"_source").value = null;
        //document.getElementById(CI_options.name).style.display = "flex";
    }else {
        alert('没有选择区域');
    }
}