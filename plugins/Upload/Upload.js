/*
 *注释：用于文件上传
 *作者：全振杰
 *
 */
var UF_options = {
    index : "UF_Name",  //后台索引名称
    method : "POST"   //服务请求 POST/GET
};

var FU_progress = {
    name : "",
    
};

function UploadFileFromDataURL(filename,dataurl,datafields,url,callback,back_type){
    var binaryString = atob(dataurl.split(',')[1]);
    var mimeType = dataurl.split(',')[0].match(/:(.*?);/)[1];
    var length = binaryString.length;
    var u8arr = new Uint8Array(length);
    while(length--) {
        u8arr[length] = binaryString.charCodeAt(length);
    }
    var blob = new Blob([u8arr.buffer], {type: mimeType});
    var FormDataObject = new FormData();
    var XMLHttpRequestObject = new XMLHttpRequest();
    FormDataObject.append(UF_options.index, blob ,filename);
    FormDataObject.append("index",UF_options.index);
    for(var i in datafields){
        FormDataObject.append(i,datafields[i]);
    }
    XMLHttpRequestObject.open(UF_options.method,url);
    XMLHttpRequestObject.onreadystatechange=function (){
        if(XMLHttpRequestObject.readyState==4 && XMLHttpRequestObject.status==200){
            switch(back_type){
                case "json":
                    callback(eval('('+XMLHttpRequestObject.responseText+')'));
                    break;
                case "text":
                    callback(XMLHttpRequestObject.responseText);
                    break;
                case "date":
                    var now_datetime = new Date(XMLHttpRequestObject.getResponseHeader('Date'));
                    var back_datetime = now_datetime.toLocaleString().replace(/[年月]/ig,"-").replace("日","").replace(/(上|下)午(\d{1,2})/ig,function(){if(arguments[1]=="下" && Number(arguments[2])<12){return 12+Number(arguments[2]);}else{return arguments[2];}});
                    callback(back_datetime);
                    break;
            }
        }
    }
    
    XMLHttpRequestObject.upload.addEventListener("progress", uploadProgress, false);
    XMLHttpRequestObject.addEventListener("load", uploadComplete, false);
    XMLHttpRequestObject.addEventListener("error", uploadFailed, false);
    XMLHttpRequestObject.addEventListener("abort", uploadCanceled, false);
    
    XMLHttpRequestObject.send(FormDataObject);
}

function UploadFileFromInputFile(inputfile,datafields,url,callback,back_type){
    var filename = inputfile.files[0].name;
    var FormDataObject = new FormData();
    var XMLHttpRequestObject = new XMLHttpRequest();
    FormDataObject.append(UF_options.index, inputfile.files[0] ,filename);
    FormDataObject.append("index",UF_options.index);
    for(var i in datafields){
        FormDataObject.append(i,datafields[i]);
    }
    XMLHttpRequestObject.open(UF_options.method,url);
    XMLHttpRequestObject.onreadystatechange=function (){
        if(XMLHttpRequestObject.readyState==4 && XMLHttpRequestObject.status==200){
            switch(back_type){
                case "json":
                    callback(eval('('+XMLHttpRequestObject.responseText+')'));
                    break;
                case "text":
                    callback(XMLHttpRequestObject.responseText);
                    break;
                case "date":
                    var now_datetime = new Date(XMLHttpRequestObject.getResponseHeader('Date'));
                    var back_datetime = now_datetime.toLocaleString().replace(/[年月]/ig,"-").replace("日","").replace(/(上|下)午(\d{1,2})/ig,function(){if(arguments[1]=="下" && Number(arguments[2])<12){return 12+Number(arguments[2]);}else{return arguments[2];}});
                    callback(back_datetime);
                    break;
            }
        }
    }
    
    XMLHttpRequestObject.upload.addEventListener("progress", uploadProgress, false);
    XMLHttpRequestObject.addEventListener("load", uploadComplete, false);
    XMLHttpRequestObject.addEventListener("error", uploadFailed, false);
    XMLHttpRequestObject.addEventListener("abort", uploadCanceled, false);
    
    XMLHttpRequestObject.send(FormDataObject);
}