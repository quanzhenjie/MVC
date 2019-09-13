<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=<?php echo $this->system_config['char']; ?>" />
    <meta http-equiv="content-language" content="zh-cn" />
    <meta name="keywords" content="<?php echo $this->system_config['keywords']; ?>" />
    <meta name="description" content="<?php echo $this->system_config['description']; ?>"/>
    <title><?php echo $this->system_config['title']; ?></title>
    <link rel="stylesheet" type="text/css" href="<?php echo THEMES_PATH; ?>/css/login.css" />
    <script type="text/javascript" src="<?php echo THEMES_PATH; ?>/scripts/jquery-3.4.1.min.js"></script>
    <script type="text/javascript" src="plugins/lhgdialog/lhgdialog.min.js?skin=iblue"></script>
    <script type="text/javascript" src="<?php echo THEMES_PATH; ?>/scripts/checklogin.js"></script>
</head>
<body>
    <div class="clear"></div>
    <div class="login_top">
        <p>
            <h1><?php echo $this->system_config['title']; ?></h1>
        </p>
    </div>
    <div class="login_middle">
        <form id="loginform" name="loginform" method="POST">
            <input type="text" id="user" name="user" class="user_input" title="请输入用户名" />
            <input type="text" id="pwd" name="pwd" class="pwd_input" title="请输入密码" />
            <div id="submit_button"></div>
        </form>
    </div>
    <div class="login_bottom">
        <span><?php echo $this->system_config['copyright']; ?></span>
    </div>   
</body>
</html>