<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
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
    <h1 class="title front-color">礼品信息</h1>
    <?php
        require_once(LIBRARIES_PATH."/CreateList.php");
        $CreateList_object = new CreateList($this->fields,$this->result_data,$this->result_page_info,$this->advanced_search_fields,array("single_delete"=>true,"multiple_delete"=>true,"excel"=>true,"custom"=>array()));
    ?>
    <div class="tab_page">
        <div class="search"><span class="order">排序：<?php echo $CreateList_object->c_order(); ?><a href="javascript:add(800,480,'发布礼品');">发布礼品</a></span><span class="search_form"><?php echo $CreateList_object->c_search(); ?><a id="advanced_search_form_open" onclick="open_advanced_search()" class="advanced_search_open">高级搜索</a></span><?php echo $CreateList_object->c_advanced_search(); ?></div>
        <div class="clear"></div>
        <div class="table_border">
            <?php echo $CreateList_object->c_table(); ?>
        </div>
    </div>
</body>
</html>