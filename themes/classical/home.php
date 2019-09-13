<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="content-type" content="text/html; charset=<?php echo $this->system_config['char']; ?>" />
    <meta http-equiv="content-language" content="zh-cn" />
    <meta name="keywords" content="<?php echo $this->system_config['keywords']; ?>" />
    <meta name="description" content="<?php echo $this->system_config['description']; ?>"/>
    <title><?php echo $this->system_config['title']; ?></title>
    <link rel="stylesheet" type="text/css" href="<?php echo THEMES_PATH; ?>/css/main.css" />
    <script type="text/javascript" src="<?php echo THEMES_PATH; ?>/scripts/jquery-3.4.1.min.js"></script>
    <script type="text/javascript" src="plugins/lhgdialog/lhgdialog.min.js?skin=mac"></script>
    <script src="<?php echo THEMES_PATH; ?>/scripts/modules.js"></script>
    <script src="<?php echo THEMES_PATH; ?>/scripts/home.js"></script>
    <style>
        .ui_state_focus .ui_title {
            color: #666;
        }
        .ui_title_bar{
            background: #ebebeb;
        }
    </style>
</head>

<body scroll="no">
<div class="header">
	<h1 class="front-color"><img src="<?php echo THEMES_PATH; ?>/images/header.png"/></h1>
	<span class="welcome"><i class="iconfont icon-yhgl"></i><?php echo $this->user['realname']; ?>，欢迎您！ </span>
</div>
<div class="iframe_bottom">
	<!--左侧导航开始-->
	<div class="iframe_left">
   		
        <ul class="accordion">
            
            <li class="son"><a href="javascript:;" onclick="GoToMainPage(this,'<?php echo BOOTSTRAP_FILE; ?>?controller=users&action=index')"><i class="iconfont icon-yonghu"></i><span>用户管理</span></a></li>
            
            <li class="father"><a href="javascript:;"><i class="iconfont icon-mulu"></i><span>积分兑换</span></a>
                <ul class="sub-menu">
                    <li><a href="javascript:;" onclick="GoToMainPage(this,'<?php echo BOOTSTRAP_FILE; ?>?controller=gifts&action=index')"><i class="iconfont icon-xiangxiajiantou min"></i><span>兑换礼品</span></a></li>
                    <li><a href="javascript:;" onclick="GoToMainPage(this,'<?php echo BOOTSTRAP_FILE; ?>?controller=orders&action=index')"><i class="iconfont icon-xiangxiajiantou min"></i><span>兑换订单</span></a></li>
                </ul>
            </li>
            <li class="father"><a href="javascript:;"><i class="iconfont icon-mulu"></i><span>一级打开</span></a>
                <ul class="sub-menu">
                    <li><a href="javascript:;" onclick="GoToMainPage(this,'')"><i class="iconfont icon-xiangxiajiantou min"></i><span>二级菜单</span></a></li>
                    <li><a href="javascript:;" onclick="GoToMainPage(this,'')"><i class="iconfont icon-xiangxiajiantou min"></i><span>二级菜单</span></a></li>
                    <li><a href="javascript:;" onclick="GoToMainPage(this,'')"><i class="iconfont icon-xiangxiajiantou min"></i><span>二级菜单</span></a></li>
                    <li><a href="javascript:;" onclick="GoToMainPage(this,'')"><i class="iconfont icon-xiangxiajiantou min"></i><span>二级菜单</span></a></li>
                </ul>
            </li>
            <li class="son"><a href="<?php echo BOOTSTRAP_FILE; ?>?controller=login&action=logout"><i class="iconfont icon-tuichu"></i><span>退出系统</span></a></li>
          </ul>
          
    </div>
    <!--左侧导航结束-->
    
    <div class="iframe_content">
    	<iframe id="contentpage" src="" class="frame-right" scrolling="yes" frameborder="0"></iframe>
    </div>
</div>
</body>
</html>
