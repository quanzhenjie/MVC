<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
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
    
    <div class="login_top">
         <h1><?php echo $this->system_config['title']; ?></h1>
    </div>
    <div class="clear"></div>
    
    <div class="login_middle">
    	<div  class="loginpic"><img src="<?php echo THEMES_PATH; ?>/images/login.png"/></div>
        
       	<img id="logo" src="<?php echo THEMES_PATH; ?>/images/logo.png"/>
        <div class="login">
        <h2><?php echo $this->system_config['title']; ?></h2>
        <form id="loginform" name="loginform" method="POST">
          <span class="inputline">
               <label for="user" id="user_label"><i class="iconfont">&#xe60c;</i></label>
              <input type="text" id="user" name="user" class="user_input" title="请输入用户名" />
          
          </span>
          <span class="inputline">
         	 <label for="pwd" id="pwd_label"><i class="iconfont">&#xe60d;</i></label>
             <input type="text" id="pwd" name="pwd" class="pwd_input" title="请输入密码" />
          </span>
            <div id="submit_button">登 录</div>
        </form>
        </div>
        
    </div>
    <div class="login_bottom">
        <span>Copyright 2019 &copy; <?php echo $this->system_config['copyright']; ?>   All Rights Reserved. </span>
    </div>
</body>
</html>