<?php
class giftsController extends Controller{
    
    protected $fields = array(
        "id" => "序号",
        "itemid" => "礼品编号",
        "itemname" => "礼品名称",
        "price" => "参考价格",
        "exchange_number" => "已兑换量",
        "exchange_points" => "已兑积分",
        "action" => "上下架",
        "publish_time" => "发布时间"
    );
    
    protected $advanced_search_fields = array(
        "id" => "number",
        "itemid" => "string",
        "itemname" => "string",
        "price" => "number",
        "exchange_number" => "number",
        "exchange_points" => "number",
        "action" => "string",
        "publish_time" => "date"
    );
    
    
    public function index(){
        $this->load_model("gifts");
        $this->model_action("index",$this->fields);
        $this->load_view(THEMES_PATH."/gifts_list.php");
    }
    
    public function excel(){
        $this->load_model("gifts");
        $this->model_action("excel",$this->fields);
        $this->excel_file_name = "礼品券信息";
        $this->load_view(VIEWS_PATH."/excel.php");
    }
    
    public function add(){
        $this->load_view(THEMES_PATH."/gifts_add.php");
    }
    
    public function insert(){
        $this->load_model("gifts");
        $result_data = $this->model_action("insert",$_POST);
        echo $this->return_json(array("result"=>$result_data));
    }
    
    public function view(){
        $this->load_model("gifts");
        $this->model_action("view");
        $this->load_view(THEMES_PATH."/gifts_view.php");
    }
    
    public function update(){
        $this->load_model("gifts");
        $result_data = $this->model_action("update",$_POST);
        echo $this->return_json(array("result"=>$result_data));
    }
    
    public function delete(){
        $this->load_model("gifts");
        $result_data = $this->model_action("delete",$_POST);
        echo $this->return_json(array("result"=>$result_data));
    }
    
}