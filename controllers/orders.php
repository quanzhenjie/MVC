<?php
class ordersController extends Controller{
    
    protected $fields = array(
        "id" => "序号",
        "orderid" => "订单编号",
        "realname" => "下单用户",
        "mobile" => "收货人手机",
        "itemid" => "礼品编号",
        "itemname" => "礼品名称",
        "itemnum" => "兑换数量",
        "points" => "消耗积分",
        "waybill_name" => "运单名称",
        "order_time" => "下单时间",
        "send_time" => "发货时间",
        "status" => "订单状态"
    );
    
    protected $advanced_search_fields = array(
        "id" => "number",
        "orderid" => "string",
        "realname" => "string",
        "mobile" => "string",
        "itemid" => "string",
        "itemname" => "string",
        "itemnum" => "number",
        "points" => "number",
        "waybill_name" => "string",
        "order_time" => "date",
        "send_time" => "date",
        "status" => "string"
    );
    
    protected $form_fields = array(
        "id" => array("label"=>"序号","type"=>"null"),
        "orderid" => array("label"=>"订单编号","type"=>"null"),
        "uid" => array("label"=>"下单用户编号","type"=>"null"),
        "realname" => array("label"=>"下单用户名称","type"=>"null"),
        "province" => array("label"=>"省","type"=>"null"),
        "city" => array("label"=>"市","type"=>"null"),
        "district" => array("label"=>"街道","type"=>"null"),
        "address" => array("label"=>"详细地址","type"=>"null"),
        "mobile" => array("label"=>"收货人手机号","type"=>"null"),
        "consignee" => array("label"=>"收货人姓名","type"=>"null"),
        "itemid" => array("label"=>"礼品编号","type"=>"null"),
        "itemname" => array("label"=>"礼品名称","type"=>"null"),
        "itemnum" => array("label"=>"礼品数量","type"=>"null"),
        "points" => array("label"=>"消耗积分","type"=>"null"),
        "waybill_name" => array("label"=>"运单名称","type"=>"text"),
        "waybill_id" => array("label"=>"运单编号","type"=>"text"),
        "remark" => array("label"=>"备注说明","type"=>"text"),
        "order_time" => array("label"=>"下单时间","type"=>"null"),
        "send_time" => array("label"=>"发货时间","type"=>"null"),
        "status" => array("label"=>"状态","type"=>"null")
    );
    
    public function index(){
        $this->load_model("orders");
        $this->model_action("index",$this->fields);
        $this->load_view(THEMES_PATH."/orders_list.php");
    }
    
    public function excel(){
        $this->load_model("orders");
        $this->model_action("excel",$this->fields);
        $this->excel_file_name = "兑换订单明细";
        $this->load_view(VIEWS_PATH."/excel.php");
    }
    
    public function view(){
        $this->load_model("orders");
        $this->model_action("view");
        $this->load_view(THEMES_PATH."/orders_view.php");
    }
    
    public function send(){
        $this->load_model("orders");
        $result_data = $this->model_action("send",$_POST);
        echo $this->return_json(array("result"=>$result_data));
    }
    
}