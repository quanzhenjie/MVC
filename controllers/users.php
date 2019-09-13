<?php
class usersController extends Controller{
    
    protected $fields = array(
        "id" => "序号",
        "username" => "用户名",
        "realname" => "真实名称",
        "powers" => "用户权限",
        "last_login_time" => "最后登录时间"
    );
    
    protected $advanced_search_fields = array(
        "id" => "number",
        "username" => "string",
        "realname" => "string",
        "powers" => "string",
        "last_login_time" => "date"
    );
    
    protected $form_fields = array(
        "id" => array("label"=>"序号","type"=>"null"),
        "username" => array("label"=>"用户名","type"=>"null"),
        "password" => array("label"=>"密码","type"=>"password"),
        "realname" => array("label"=>"真实名称","type"=>"text"),
        "gender" => array("label"=>"性别","type"=>"radio","options"=>array(array("text"=>"男","value"=>"男","checked"=>false),array("text"=>"女","value"=>"女","checked"=>false))),
        "educational_background" => array("label"=>"学历","type"=>"select","multiple"=>false,"onchange"=>"","options"=>array(array("text"=>"博士","value"=>"博士","selected"=>false),array("text"=>"研究生","value"=>"研究生","selected"=>false),array("text"=>"本科","value"=>"本科","selected"=>false),array("text"=>"大专","value"=>"大专","selected"=>false))),
        "hobby" => array("label"=>"爱好","type"=>"select","multiple"=>true,"separate"=>"|","options"=>array(array("text"=>"旅游","value"=>"旅游","selected"=>false),array("text"=>"看电影","value"=>"看电影","selected"=>false),array("text"=>"玩游戏","value"=>"玩游戏","selected"=>false))),
        "powers" => array("label"=>"用户权限","type"=>"checkbox","separate"=>"|","options"=>array(array("text"=>"普通会员","value"=>"普通会员","checked"=>false),array("text"=>"高级会员","value"=>"高级会员","checked"=>false))),
        "last_login_time" => array("label"=>"最后登录时间","type"=>"null")
    );
    
    protected $form_list_fields = array(
        "users_id" => array("label"=>"所属父类项","type"=>"hidden"),
        "gongke" => array("label"=>"功课","type"=>"text","required"=>true),
        "fenshu" => array("label"=>"分数","type"=>"text","required"=>true),
        "chengji" => array("label"=>"成绩","type"=>"select","required"=>true,"multiple"=>false,"options"=>array(array("text"=>"==请选择==","value"=>"","selected"=>false),array("text"=>"优秀","value"=>"优秀","selected"=>false),array("text"=>"及格","value"=>"及格","selected"=>false),array("text"=>"不及格","value"=>"不及格","selected"=>false))),
        "if_bukao" => array("label"=>"是否补考","type"=>"radio","required"=>true,"options"=>array(array("text"=>"是","value"=>"是","checked"=>false),array("text"=>"否","value"=>"否","checked"=>false)))
    );
    
    public function index(){
        $this->load_model("users");
        $this->model_action("index",$this->fields);
        $this->load_view(THEMES_PATH."/users_list.php");
    }
    
    public function excel(){
        $this->load_model("users");
        $this->model_action("excel",$this->fields);
        $this->excel_file_name = "用户列表";
        $this->load_view(VIEWS_PATH."/excel.php");
    }
    
    public function add(){
        array_splice($this->form_fields,0,1);
        $this->form_fields['username'] = array("label"=>"用户名","type"=>"text");
        array_splice($this->form_fields,7,1);
        $this->load_view(THEMES_PATH."/users_add.php");
    }
    
    public function insert(){
        $this->load_model("users");
        $result_data = $this->model_action("insert",$_POST);
        echo $this->return_json(array("result"=>$result_data));
    }
    
    public function list_insert(){
        $this->load_model("users");
        $result_data = $this->model_action("list_insert",$_POST);
        echo $this->return_json(array("result"=>$result_data));
    }
    
    public function view(){
        $this->load_model("users");
        $this->model_action("view");
        $this->load_view(THEMES_PATH."/users_view.php");
    }
    
    public function update(){
        $this->load_model("users");
        $result_data = $this->model_action("update",$_POST);
        echo $this->return_json(array("result"=>$result_data));
    }
    
    public function delete(){
        $this->load_model("users");
        $result_data = $this->model_action("delete",$_POST);
        echo $this->return_json(array("result"=>$result_data));
    }
    
}