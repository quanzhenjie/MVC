<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=<?php echo $this->system_config['char']; ?>" />
    <meta http-equiv="content-language" content="zh-cn" />
    <meta name="keywords" content="<?php echo $this->system_config['keywords']; ?>" />
    <meta name="description" content="<?php echo $this->system_config['description']; ?>"/>
    <title><?php echo $this->system_config['title']; ?></title>
    
</head>
<body>
    <?php
        echo "<h2>用户登出</h2>";
        echo "<hr />";
        echo "0秒后跳转到登录界面";
    ?>
    <script type="text/javascript">
        window.location.href = "<?php echo BOOTSTRAP_FILE; ?>?controller=login&action=index";
    </script>
</body>
</html>