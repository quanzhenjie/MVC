<?php
class homeController extends Controller{
    
    public function index(){
        $this->load_view(THEMES_PATH."/home.php");
        
    }
}