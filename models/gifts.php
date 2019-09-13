<?php
class giftsModel extends Model{
    
    public function index($field){
        $sql = null;
        $where = $this->search_where();
        $order = $this->search_order();
        $this->table_page(18,"gifts",$sql,$where,$order);
        return $this->table_page_data;
    }
    
    public function excel($field){
        $sql = null;
        $where = $this->search_where();
        $order = $this->search_order();
        $this->table_page(0,"gifts",$sql,$where,$order);
        return $this->table_page_data;
    }
    
    public function insert($post){
        $post['exchange_number'] = 0;
        $post['publish_time'] = date("Y-m-d H:i:s",time());
        $result_data = $this->db->table("gifts")->add($post);
        $this->add_debug("新增一条id为".$result_data."的礼品记录");
        return $result_data;
    }
    
    public function view(){
        $result_data = $this->db->table("gifts")->where("id = '".$_GET['id']."'")->find();
        $this->add_debug("id为".$_GET['id']."的礼品资料");
        return $result_data;
    }
    
    public function update($post){
        $id = $post['id'];
        unset($post['id']);
        $result_data = $this->db->table("gifts")->where("id = ".$id)->update($post);
        $this->add_debug("更新一条id为".$post['id']."的礼品记录");
        return $id;//$result_data>0?$result_data:0;
    }
    
    public function delete($post){
        /*
        $thumbs = $this->db->table("gifts")->field("thumb_path")->where($post['where'])->findAll();
        $this->add_debug("查询要删除的礼品记录");
        foreach($thumbs as $thumb){
            if($thumb['thumb_path']){
                @unlink($thumb['thumb_path']);
            }
        }
        */
        $result_data = $this->db->table("gifts")->where($post['where'])->delete();
        $this->add_debug("删除where = ".$post['where']."的礼品记录");
        return $result_data>0?$result_data:0;
    }
}