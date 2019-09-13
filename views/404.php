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
        echo "<h2>Error404</h2>";
        echo "<hr />";
        echo "不存在页面";
        $page_error = isset($page_error)?$page_error:false;
    ?>
    <script type="text/javascript">
        var currentURL = window.location.href;
        if(currentURL.replace(/^http:.+?\/<?php echo str_replace(".","\.",BOOTSTRAP_FILE); ?>\?/,"<?php echo BOOTSTRAP_FILE; ?>?") == "<?php echo BOOTSTRAP_FILE; ?>?controller=login&action=logout" || <?php echo $page_error?"true":"false"; ?>){
            window.location.href = "<?php echo BOOTSTRAP_FILE; ?>?controller=login&action=index";
        }else{
            window.location.href = "<?php echo BOOTSTRAP_FILE; ?>?controller=login&action=index&redirectURL="+escape(currentURL);
        }
    </script>
</body>
</html>