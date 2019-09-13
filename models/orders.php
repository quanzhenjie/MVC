<?php
class ordersModel extends Model{
    
    public function index($field){
        $sql = null;
        $where = $this->search_where();
        $order = $this->search_order(array("field"=>"id","order"=>"asc"));
        $this->table_page(18,"orders",$sql,$where,$order);
        return $this->table_page_data;
    }
    
    public function excel($field){
        $sql = null;
        $where = $this->search_where();
        $order = $this->search_order();
        $this->table_page(0,"orders",$sql,$where,$order);
        return $this->table_page_data;
    }
    
    public function view(){
        $result_data = $this->db->table("orders")->where("id = '".main::$id."'")->find();
        $this->add_debug("id为".main::$id."的订单资料");
        return $result_data;
    }
    
    public function send($post){
        $id = $post['id'];
        unset($post['id']);
        $post['status'] = "已发货";
        $result_data = $this->db->table("orders")->where("id = ".$id)->update($post);
        $this->add_debug("id为".$post['id']."的订单发货记录");
        return $result_data>0?$result_data:0;
    }
}