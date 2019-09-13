<?php
class loginModel extends Model{
    
    public function login($post){
        $user = $this->db->table("users")->where("username = '".$post['username']."' and password = '".md5($post['password'])."'")->find();
        $this->add_debug("用户登录验证...");
        if($user['id']){
            $this->db->table("users")->where("id = ".$user['id'])->update(array("last_login_time"=>date("Y-m-d H:i:s",time())));
            $this->add_debug("更新该用户最后登录时间");
            return $user;
        }else{
            return null;
        }
    }
}