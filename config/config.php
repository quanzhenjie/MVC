<?php
/**
 * 系统配置文件
 * @copyright   Copyright(c) 2013
 * @author      全振杰
 * @version     1.0
 */

//定义常用常量
define('FRONT_AND_AFTER',true);//默认：true 前后端不分离登录用SESSION，false 前后端分离，登录用token，token以POST传值，如有需要在请求头，自行改造

define('BOOTSTRAP_FILE',"index.php");//入口文件名称
define('CONFIG_PATH', dirname(__FILE__));//配置文件目录
define('SYSTEM_PATH',  substr(CONFIG_PATH, 0,-7));//系统根目录
define('CONTROLLERS_PATH',  SYSTEM_PATH."/controllers");//控制器目录
define('MODELS_PATH',  SYSTEM_PATH."/models");//模型层目录
define('VIEWS_PATH',  SYSTEM_PATH."/views");//视图层目录
define('LIBRARIES_PATH',  SYSTEM_PATH."/libraries");//自定义类库目录
define('COMPONENTS_PATH',  SYSTEM_PATH."/components");//组件目录
define('PLUGINS_PATH',  SYSTEM_PATH."/plugins");//插件目录
define('THEMES_PATH',  "themes/classical");//前台模板主题引用相对路径

//数据库配置
$config['db'] = array(
    "type" => "mysql",   //数据库类型，这里填写mysql或者mssql
    "host" => "localhost",   //数据库地址
    "user" => "root",   //数据库登录用户名
    "pwd" => "",    //数据库登录密码
    "port" => null,    //数据库端口 如非特别，默认为空
    "database" => "mvc",    //数据库名
    "prefix" => "", //数据表前缀
    "char" => "utf8",   //字符集  例如：gbk,gb2312或者utf8  如果type为mssql那么此处默认为gb2312
    "pconnect" => false //是否开启持久性连接 默认为false
);

//系统配置
$config['system'] = array(
    //前后端分离时启用
    "token" => array(
        "Table" => "users",
        "Field" => "token",
        "Primary" => "id",
        "TimeField" => "last_login_time",
        "TimeMinute" => 30
    ),
    "domain" => "mvc",  //系统唯一域名
    "debug" => false,    //是否开启调试模式 true开启 false关闭
    "title" => "system_title",  //系统名称，<title />标签内容
    "char" => "UTF-8",   //与数据库配置中的字符串保持一致，例如：GBK,GB2312或者UTF-8
    "keywords" => "",    //网页关键字，利于SEO优化 默认可以为空
    "description" => "",    //网页简单描述，利于SEO优化 默认可以为空
    "copyright" => "" //版权信息
);