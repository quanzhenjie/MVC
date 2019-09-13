<?php
class usersModel extends Model{
    
    public function index($field){
        $sql = null;
        $where = $this->search_where();
        $order = $this->search_order();
        $this->table_page(18,"users",$sql,$where,$order);
        return $this->table_page_data;
    }
    
    public function excel($field){
        $sql = null;
        $where = $this->search_where();
        $order = $this->search_order();
        $this->table_page(0,"users",$sql,$where,$order);
        return $this->table_page_data;
    }
    
    public function insert($post){
        $post['password'] = md5($post['password']);
        $result_data = $this->db->table("users")->add($post);
        $this->add_debug("新增一条id为".$result_data."的用户记录");
        return $result_data;
    }
    
    public function list_insert($post){
        $result_data = $this->db->table("gongke")->add($post);
        $this->add_debug("新增一条id为".$result_data."的功课记录");
        return $result_data;
    }
    
    public function view(){
        $result_data = $this->db->table("users")->where("id = '".$_GET['id']."'")->find();
        $this->add_debug("id为".$_GET['id']."的用户资料");
        $this->form_list_result_data = $this->db->table("gongke")->where("users_id = '".$_GET['id']."'")->findAll();
        $this->add_debug("users_id为".$_GET['id']."的功课记录");
        return $result_data;
    }
    
    public function update($post){
        $id = $post['id'];
        unset($post['id']);
        $result_data = $this->db->table("users")->where("id = ".$id)->update($post);
        $this->add_debug("更新一条id为".$post['id']."的用户记录");
        $affectedRows = $this->db->table("gongke")->where("users_id = '".$id."'")->delete();
        $this->add_debug("清除所有users_id为".$id."的功课记录,影响数为".$affectedRows);
        return $result_data>0?$result_data:0;
    }
    
    public function delete($post){
        $result_data = $this->db->table("users")->where($post['where'])->delete();
        $this->add_debug("删除where = ".$post['where']."的用户记录");
        return $result_data>0?$result_data:0;
    }
}