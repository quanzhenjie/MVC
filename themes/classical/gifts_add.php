<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=<?php echo $this->system_config['char']; ?>" />
    <meta http-equiv="content-language" content="zh-cn" />
    <meta name="keywords" content="..." />
    <meta name="description" content="..."/>
    <title><?php echo $this->system_config['title']; ?></title>
    <link rel="stylesheet" type="text/css" href="<?php echo THEMES_PATH; ?>/css/modules.css" />
    <script type="text/javascript" src="<?php echo THEMES_PATH; ?>/scripts/jquery-3.4.1.min.js"></script>
    <script language="javascript" type="text/javascript" src="<?php echo THEMES_PATH; ?>/scripts/jquery-migrate-1.4.1.min.js"></script>
    <script language="javascript" type="text/javascript" src="plugins/My97DatePicker/WdatePicker.js"></script>
    <script language="javascript" type="text/javascript" src="plugins/xheditor/xheditor-1.2.1.min.js"></script>
    <script language="javascript" type="text/javascript" src="plugins/xheditor/xheditor_lang/zh-cn.js"></script>
    <script type="text/javascript" src="<?php echo THEMES_PATH; ?>/scripts/modules.js"></script>
    <style>
        body{
            background: #F3F3F3;
            
        }
        
    </style>
    <script type="text/javascript">
        if(window.parent.WindowObject){
            window.parent.WindowObject.size("800px","480px");
            window.parent.WindowObject.title("发布礼品");
        }
    </script>
</head>
<body>
    
    <div class="form_table">
        <div class="form-item">
            <label>礼品编号</label>
            <input type="text" id="itemid" class="txt-input" value="" /><font color="red">自定义不允许重复</font>
        </div>
        <div class="form-item">
            <label>礼品名称</label>
            <input type="text" id="itemname" class="txt-input" value="" /><font color="red">包含规格，颜色等</font>
        </div>
        <div class="form-item">
            <label>礼品图片</label>
            <form id="upload_image_form" method="POST" action="index.php?controller=upload&action=gifts_thumb" target="upload_image_iframe" enctype="multipart/form-data"><input type="file" id="thumb_path" name="thumb_path" class="txt-input" value="上传图片" /><font color="red">分辨率：300*300</font><input type="hidden" id="index" name="index" value="thumb_path" /><input type="hidden" id="table_id" name="table_id" value="<?php echo $_GET['id']; ?>" /></form>
            <iframe id="upload_image_iframe" name="upload_image_iframe" src="" style="height: 0px;border: 0px;display: none;"></iframe>
        </div>
        <div class="form-item">
            <label>参考价格</label>
            <input type="text" id="price" class="txt-input" value="" />
        </div>
        <div class="form-item">
            <label>兑换积分</label>
            <input type="number" id="exchange_points" class="txt-input" value="" /><font color="red">兑换所需的积分数</font>
        </div>
        <div class="form-item">
            <label>上架下架</label>
            <select id="action"><option value="上架" selected="selected">上架</option><option value="下架">下架</option></select> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <font color="red">默认为上架，下架时该礼品微信端不显示</font>
        </div>
        <div style="text-align: center;">
            <h1 class="title front-color">礼品详情</h1>
            <textarea id="detail" style="width: 100%;height: 480px;"></textarea>
        </div>
    </div>
    <script type="text/javascript">
        var xheditor_LinkUrl = "index.php?controller=upload&action=xheditor&table_name=gifts&pid=0";
        var xheditor = $('#detail').xheditor({upLinkUrl:xheditor_LinkUrl,upLinkExt:"zip,rar,txt",upImgUrl:xheditor_LinkUrl,upImgExt:"jpg,jpeg,gif,png",upFlashUrl:xheditor_LinkUrl,upFlashExt:"swf",upMediaUrl:xheditor_LinkUrl,upMediaExt:"avi"});
        
        function add_gift(){
            if($("#itemid").val().length<1){
                $("#warn").html("<font color='red'>礼品编号为必填项</font>");
                return false;
            }
            if($("#itemname").val().length<1){
                $("#warn").html("<font color='red'>礼品名称为必填项</font>");
                return false;
            }
            if($("#thumb_path").val().length<1 || !/.+\.(jpg|png|gif)$/.exec($("#thumb_path").val())){
                $("#warn").html("<font color='red'>请上传礼品图片，支持格式为：jpg,png,gif三种</font>");
                return false;
            }
            if($("#price").val().length<1){
                $("#warn").html("<font color='red'>参考价格为必填项</font>");
                return false;
            }
            if($("#exchange_points").val().length<1 || Number($("#exchange_points").val())<0){
                $("#warn").html("<font color='red'>兑换积分必须为正数并为必填项</font>");
                return false;
            }
            $.post("index.php?controller="+url_get("controller")+"&action=insert",{"itemid":$("#itemid").val(),"itemname":$("#itemname").val(),"price":$("#price").val(),"exchange_points":$("#exchange_points").val(),"detail":xheditor.getSource(),"action":$("#action").val()},function(back_data){
                show_debug(back_data.debug);
                logout(back_data.login);
                if(back_data.result){
                    $("#table_id").val(back_data.result);
                    $("#upload_image_form").submit();
                    window.parent.IframeReload();
                }else{
                    alert("发布礼品失败");
                }
            },"json");
        }
        
        function upload_back(result_data){
            if(!(result_data.result && result_data.sql_result)){
                alert("图片上传失败,请重新上传一次");
            }
            alert("发布礼品成功");
            window.location.href = "index.php?controller="+url_get("controller")+"&action=view&id="+result_data.id;
        }
    </script>
    <div class="buttons">
        <input type="button" value="发布保存" onclick="add_gift()" /> <input type="button" value="刷新" onclick="window.location.reload()" /> <span id="warn"></span>
    </div>
</body>
</html>