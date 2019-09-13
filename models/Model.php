<?php
/**
 * 模型层主类文件
 * @copyright   Copyright(c) 2013
 * @author      全振杰
 * @version     1.0
 */
class Model {
    
    protected $db;
    
    public $user;
    
    protected $table_page_data = array();
    
    public $additional_result_data = array();
    
    public $table_page_info = array("totalrow"=>null,"totalpage"=>null,"currentpage"=>null);
    
    public $form_list_result_data = array(array(),array(),array());
    
    private $debug = array();
    
    public function __construct(){
        require_once (LIBRARIES_PATH."/sql.php");
        $this->db = new Db();
    }
    
    public function connect_db($config){
        $this->db->connect($config);
    }
    
    protected function search_where($default=null){
        $return_string = "";
        if(main::$where && $default){
            $return_string = "(".main::$where.") and (".$default.")";
        }else{
            $return_string = main::$where.$default;
        }
        return $return_string;
    }
    
    protected function search_order($default=array("field"=>"id","order"=>"desc")){
        $return_array = array();
        if(main::$order){
            $order_array = explode(" ",main::$order);
            $return_array[] = array("field"=>$order_array[0],"order"=>$order_array[1]);
            if($order_array[0]!=$default['field']){
                $return_array[] = $default;
            }
        }else{
            $return_array[] = $default;
        }
        return $return_array;
    }
    
    protected function table_page($page_size=15,$table_name=null,$sql=null,$where=null,$order=array()){
        $i=0;
        $asc = "";
        foreach($order as $order_field){
            $i++;
            $asc .= ($i==1?"":",").$order_field['field']." ".$order_field['order'];
        }
        $i=0;
        $desc = "";
        foreach($order as $order_field){
            $i++;
            $desc .= ($i==1?"":",").$order_field['field']." ".($order_field['order']=="asc"?"desc":"asc");
        }
        if($page_size>=1){
            $total_arr = $this->db->custom_select("select count(*) as totalrow from ".($sql?"(select ".$sql.($where?" where ".$where:"").") as temp":$table_name.($where?" where ".$where:"")),false);
            $this->table_page_info['totalrow'] = $total_arr['totalrow'];
            $this->add_debug("总记录数");
            $max_page = ceil($total_arr['totalrow']/$page_size);//最大页数
            $this->table_page_info['totalpage'] = $max_page;
            $this->table_page_info['currentpage'] = main::$page;
            if($this->db->SqlType=="mysql"){
                $result_arr = $this->db->custom_select("select ".($sql?" ".$sql:" * from ".$table_name).($where?" where ".$where:"")." order by ".$asc." limit ".($page_size*(main::$page-1)).",".($max_page==main::$page && $total_arr['totalrow']%$page_size>0?$total_arr['totalrow']%$page_size:$page_size),true);
            }else{
                $result_arr = $this->db->custom_select("select * from (select top ".($max_page==main::$page && $total_arr['totalrow']%$page_size>0?$total_arr['totalrow']%$page_size:$page_size)." * from (select top ".($page_size*main::$page).($sql?" ".$sql:" * from ".$table_name).($where?" where ".$where:"")." order by ".$asc.") as temp order by ".preg_replace("@(^|,)\w+?\.@","",$desc).") as temp order by ".preg_replace("@(^|,)\w+?\.@","",$asc),true);
            }
            $this->add_debug("当前页所有记录详细数据");
        }else{
            $result_arr = $this->db->custom_select("select ".($sql?" ".$sql:" * from ".$table_name).($where?" where ".$where:"")." order by ".$asc,true);
            $this->add_debug("所有记录详细数据");
        }
        $this->table_page_data = $result_arr;
    }
    
    protected function add_debug($note){
        array_push($this->debug,array("sql"=>$this->db->sql,"note"=>$note));
    }
    
    public function back_debug(){
        return $this->debug;
    }
    //前后端分离时启用，需要修改表名和字段适用于自己的项目
    public function login_authentication($token,$config){
        $admin = $this->db->table($config['Table'])->where($config['Field']." = '".$token."'")->find();
        if($admin[$config['Primary']] && strtotime($admin[$config['TimeField']])+$config['TimeMinute']*60 > time()){
            $this->db->table($config['Table'])->where($config['Primary']." = '".$admin[$config['Primary']]."'")->update(array($config['TimeField']=>date("Y-m-d H:i:s",time())));
            return 1;
        }else{
            return 0;
        }
    }
    
}