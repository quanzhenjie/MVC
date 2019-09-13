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
    <script type="text/javascript" src="<?php echo THEMES_PATH; ?>/scripts/modules.js"></script>
    <script language="javascript" type="text/javascript" src="plugins/My97DatePicker/WdatePicker.js"></script>
    <style>
        body{
            background: #FFFFFF;
        }
    </style>
    <script type="text/javascript">
        if(window.parent.WindowObject){
            window.parent.WindowObject.size("800px","480px");
            window.parent.WindowObject.title("兑换订单详情");
        }
    </script>
</head>
<body>
    <?php
        require_once(LIBRARIES_PATH."/CreateForm.php");
        $CreateForm_object = new CreateForm($this->form_fields,$this->result_data,array(array("value"=>"刷新","onclick"=>"window.location.reload()")),$this->form_list_fields,$this->form_list_result_data);
    ?>
    <script type="text/javascript">
        function SendGifts(){
            if(confirm("确认发货吗？")){
                $.post("index.php?controller="+url_get("controller")+"&action=send",{"id":$("#id").val(),"waybill_name":$("#waybill_name").val(),"waybill_id":$("#waybill_id").val(),"remark":$("#remark").val()},function(back_data){
                    show_debug(back_data.debug);
                    logout(back_data.login);
                    if(back_data.result){
                        alert("保存成功");
                        window.parent.IframeReload();
                        window.location.reload();
                    }else{
                        alert("保存失败");
                    }
                },"json");
            }
        }
    </script>
    <div style="text-align: center;">
        <input type="hidden" id="id" value="<?php echo $this->result_data['id']; ?>" />
        <img src="<?php echo $this->result_data['thumb_path']; ?>" width="300" height="300" />
    </div>
    <div class="form_table">
        <?php
            echo $CreateForm_object->c_form();
        ?>
    </div>
    <div class="buttons">
        <input type="button" value="确认发货" onclick="SendGifts()" /> <input type="button" value="刷新" onclick="window.location.reload()" />
    </div>
</body>
</html>