<?php
    /****
            入口文件
    ****/
    require (dirname(__FILE__).'/config/config.php');
    require (dirname(__FILE__).'/controllers/main.php');
    Main::init($config);
?>