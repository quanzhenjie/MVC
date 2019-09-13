<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=<?php echo $this->system_config['char']; ?>" />
    <meta http-equiv="content-language" content="zh-cn" />
    <meta name="keywords" content="<?php echo $this->system_config['keywords']; ?>" />                            
    <meta name="description" content="<?php echo $this->system_config['description']; ?>" />
    <title><?php echo $this->system_config['title']; ?></title>
    <link rel="stylesheet" type="text/css" href="<?php echo THEMES_PATH; ?>/css/style.css" />
    <script type="text/javascript" src="<?php echo THEMES_PATH; ?>/scripts/jquery-3.4.1.min.js"></script>
    <script type="text/javascript" src="plugins/lhgdialog/lhgdialog.min.js?skin=iblue"></script>
    <script type="text/javascript" src="<?php echo THEMES_PATH; ?>/scripts/home.js"></script>
    <!--[if IE 6]>
	<script type="text/javascript" src="scripts/DD_belatedPNG_0.0.8a-min.js"></script>
	<script type="text/javascript">
		DD_belatedPNG.fix(".menu div,.exit a,.exit a:hover,img,background");
	</script>
    <![endif]-->
</head>
<body scroll="no">
    <div class="top">
        <div class="menu">
            
            <div onclick="users_manage()"><img src="<?php echo THEMES_PATH; ?>/images/users.png" /><span>用户管理</span></div>
        </div>
        <script type="text/javascript">
            $(".menu div").css("background","transparent");
        </script>
        <div class="info">
            <div class="user_info"><span><?php echo $this->user['realname']; ?>，欢迎您！</span><p class="exit"><a href="<?php echo BOOTSTRAP_FILE; ?>?controller=login&action=logout"></a></p></div>
            <div class="system_info"><span><?php echo $this->system_config['title']; ?></span></div>
        </div>
    </div>
    
    <div class="clear"></div>
    
    <div class="copyright"><span><?php echo $this->system_config['copyright']; ?></span></div>
</body>
</html>