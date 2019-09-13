<?php
class loginController extends Controller{
    
    public function index(){
        $this->load_view(THEMES_PATH."/login.php",false);
    }
    
    public function login(){
        $this->load_model("login");
        $result_data = $this->model_action("login",$_POST,false);
        if(count($result_data)>2){
            $_SESSION[$this->system_config['domain']] = $result_data;
            echo $this->return_json(array("result"=>"1"));
        }else{
            echo $this->return_json(array("result"=>"0"));
        }
    }
    
    public function logout(){
        $_SESSION[$this->system_config['domain']] = null;
        $this->load_view(VIEWS_PATH."/logout.php",false);
    }
}