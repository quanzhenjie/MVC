<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=<?php echo $this->system_config['char']; ?>" />
    <meta http-equiv="content-language" content="zh-cn" />
    <meta name="keywords" content="<?php echo $this->system_config['keywords']; ?>" />
    <meta name="description" content="<?php echo $this->system_config['description']; ?>"/>
    <title><?php echo $this->system_config['title']; ?></title>
    <link rel="stylesheet" type="text/css" href="<?php echo THEMES_PATH; ?>/css/modules.css" />
    <script type="text/javascript" src="<?php echo THEMES_PATH; ?>/scripts/jquery-3.4.1.min.js"></script>
    <script language="javascript" type="text/javascript" src="plugins/My97DatePicker/WdatePicker.js"></script>
    <script type="text/javascript" src="<?php echo THEMES_PATH; ?>/scripts/modules.js"></script>
    
</head>
<body>
    <?php
        require_once(LIBRARIES_PATH."/CreateForm.php");
        $CreateForm_object = new CreateForm($this->form_fields,$this->result_data,array(array("value"=>"保存","onclick"=>"users_add()"),array("value"=>"返回列表","onclick"=>"back_list()")),$this->form_list_fields,$this->form_list_result_data);
    ?>
    <script type="text/javascript">
        function users_add(){
            <?php
                echo $CreateForm_object->c_js_check();
            ?>
            $.post("index.php?controller="+url_get("controller")+"&action=insert",{<?php echo $CreateForm_object->c_post_send(); ?>},function(back_data){
                show_debug(back_data.debug);
                logout(back_data.login);
                if(back_data.result){
                    <?php
                        echo $CreateForm_object->c_js_list("back_data.result","list_insert");
                    ?>
                    alert("保存成功");
                    view(back_data.result);
                }else{
                    alert("保存失败");
                }
            },"json");
        }
    </script>
    <div class="form_table">
        <?php
            echo $CreateForm_object->c_form();
            echo $CreateForm_object->c_list();
        ?>
    </div>
    <?php echo $CreateForm_object->c_buttons(); ?>
</body>
</html>