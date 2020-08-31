<?php
/**
 * 控制器主类文件
 * @copyright   Copyright(c) 2013
 * @author      全振杰
 * @version     1.0
 */
class Controller {
    
    private $db_config;
    
    protected $system_config;
    
    private $model_object;
    
    protected $user = array();
    
    protected $fields = array();
    
    protected $advanced_search_fields = array();
    
    protected $form_fields = array();
    
    protected $form_list_fields = array();
    
    protected $excel_file_name = "";
    
    protected $result_data = array();
    
    protected $additional_result_data = array();
    
    protected $form_list_result_data = array(array(),array(),array());
    
    protected $result_page_info = array();
    
    protected $debug = array();
    
    public function __construct(){
        $this->excel_file_name = main::$controller;
    }
    
    public function load_config($config){
        $this->db_config = $config['db'];
        $this->system_config = $config['system'];
        if($this->system_config['char'] == "GB2312"){
            header("Content-type: text/html; charset=GBK");
            foreach($_POST as $key=>$value){
                $_POST[$key] = iconv("utf-8","gb2312//ignore",stripslashes($value));
            }
        }else{
            header("Content-type: text/html; charset=UTF-8");
            foreach($_POST as $key=>$value){
                $_POST[$key] = stripslashes($value);
            }
        }
    }
    
    protected function login_authentication(){
        if(FRONT_AND_AFTER){
            if(is_array($_SESSION[$this->system_config['domain']]) && count($_SESSION[$this->system_config['domain']])>2){
                $this->user = $_SESSION[$this->system_config['domain']];
                return true;
            }else{
                $_SESSION[$this->system_config['domain']] = null;
                return 0;
            }
        }else{
            if(@$_POST[$this->system_config['token']['Field']]){
                require_once (MODELS_PATH."/Model.php");
                $this->model_object = new Model();
                $this->model_object->connect_db($this->db_config);
                return $this->model_object->login_authentication(@$_POST[$this->system_config['token']['Field']],$this->system_config['token']);
            }else{
                return 0;
            }
        }
    }
    
    protected function load_view($path,$login=true){
        if(FRONT_AND_AFTER && $login && !$this->login_authentication()){
            require_once (VIEWS_PATH."/404.php");
        }else{
            require_once ($path);
        }
        if($this->system_config['debug']){
            require_once (VIEWS_PATH."/debug.php");
        }
    }
    
    protected function return_json($data=array()){
        $json = "{";
        $data_length = count($data);
        $i=0;
        foreach($data as $key=>$val){
            $i++;
            $json .= "\"".$key."\":".(is_array($val)?$this->ArrayToJson($val):"\"".$this->ValidJsonValue($val)."\"").($i==$data_length?"":",");
        }
        $json .= ($data_length<1?"":",")."\"login\":".$this->login_authentication();
        if($this->system_config['debug']){
            $json .= ",\"debug\":[";
            foreach($this->debug as $debug_list){
                $j++;
                $json .= '{"sql":"'.$debug_list['sql'].'","note":"'.$debug_list['note'].'"}'.($j==count($this->debug)?"":",");
            }
            $json .= "]";
        }
        $json .= "}";
        return $json;
    }
    private function ArrayToJson($data=array()){
        if(count($data) == 0 || is_array(@$data[0])){
            $json = "[";
            $i=0;
            foreach($data as $array_data){
                $i++;
                $json .= "{";
                $j=0;
                foreach($array_data as $key=>$val){
                    $j++;
                    $json .= '"'.$key.'":'.(is_array($val)?$this->ArrayToJson($val):"\"".$this->ValidJsonValue($val)."\"").''.($j==count($array_data)?"":",");
                }
                $json .= "}".($i==count($data)?"":",");
            }
            $json .= "]";
        }else{
            $json = "{";
            $k=0;
            foreach($data as $key=>$val){
                $k++;
                $json .= '"'.$key.'":"'.$this->ValidJsonValue($val).'"'.($k==count($data)?"":",");
            }
            $json .= "}";
        }
        return $json;
    }
    private function ValidJsonValue($value=""){
        $value = str_replace("\"","\\\"",str_replace("\\","\\\\",$value));
        $value = preg_replace("@\r\n@","",$value);
        return $value;
    }
    
    protected function load_model($model){
        require_once (MODELS_PATH."/Model.php");
        require_once (MODELS_PATH."/".$model.".php");
        $model_object_name = $model."Model";
        $this->model_object = new $model_object_name;
        $this->model_object->connect_db($this->db_config);
    }
    
    protected function model_action($action,$parameters=null,$login=true){
        if(FRONT_AND_AFTER && $login && !$this->login_authentication()){
            $this->result_data = null;
        }else{
            $this->model_object->user = $this->user;
            $this->result_data = $this->model_object->$action($parameters);
            $this->additional_result_data = $this->model_object->additional_result_data;
            $this->result_page_info = $this->model_object->table_page_info;
            $this->form_list_result_data = $this->model_object->form_list_result_data;
            $this->debug = $this->model_object->back_debug();
        }
        return $this->result_data;
    }
}