<?php
/**
 * 主类文件
 * @copyright   Copyright(c) 2013
 * @author      全振杰
 * @version     1.0
 */
class Main {
    
    public static $controller;
    
    public static $action;
    
    public static $order;
    
    public static $where;
    
    public static $page;
    
    public static $id;
    
    private static $config;
    
    private static $controller_object;
    
    public static function init($config){
        session_start();
        date_default_timezone_set('PRC');
        self::$controller = isset($_GET['controller'])?$_GET['controller']:null;
        self::$action = isset($_GET['action'])?$_GET['action']:null;
        self::$order = isset($_GET['order'])?$_GET['order']:null;
        self::$where = isset($_GET['where'])?stripslashes($_GET['where']):null;
        self::$page = (isset($_GET['page']) && $_GET['page']>1?$_GET['page']:1);
        self::$id = (isset($_GET['id']) && is_numeric($_GET['id'])?$_GET['id']:null);
        self::$config = $config;
        self::GotoController();
    }
    
    private static function GotoController(){
        require_once (CONTROLLERS_PATH."/"."Controller.php");
        $controller_file_path = CONTROLLERS_PATH."/".self::$controller.".php";
        if(file_exists($controller_file_path) && strlen(self::$controller)>0){
            require_once ($controller_file_path);
            $controller_object_name = self::$controller."Controller";
            self::$controller_object = new $controller_object_name;
            if(self::$action && method_exists($controller_object_name,self::$action)){
                $method_name = self::$action;
                self::$controller_object->load_config(self::$config);
                self::$controller_object->$method_name();
            }else{
                self::Goto404("The controller method does not exist");//控制器方法不存在
            }
        }else{
            self::Goto404("The controller does not exist");//控制器不存在
        }
    }
    
    private static function Goto404($cause){
        require_once (CONTROLLERS_PATH."/"."Error_404.php");
        self::$controller_object = new Error_404($cause);
    }
}