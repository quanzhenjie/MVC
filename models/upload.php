<?php
class uploadModel extends Model{
    
    public function xheditor($image_info){
        $image_info['upload_time'] = date("Y-m-d H:i:s",time());
        $result_data = $this->db->table("xheditor")->add($image_info);
        $this->add_debug("添加上传文件的信息到数据库");
        return $result_data;
    }
    
    public function gifts_thumb($post){
        /*
        $thumb = $this->db->table("gifts")->field("thumb_path")->where("id= '".$post['id']."'")->find();
        if($thumb['thumb_path']){
            @unlink($thumb['thumb_path']);
        }
        */
        $result_data = $this->db->table("gifts")->where("id= '".$post['id']."'")->update(array("thumb_path"=>$post['path']));
        $this->add_debug("更新礼品缩略图路径");
        return $result_data;
    }
    
}