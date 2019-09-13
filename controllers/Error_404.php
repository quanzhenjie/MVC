<?php
/**
 * 错误404文件
 * @copyright   Copyright(c) 2013
 * @author      全振杰
 * @version     1.0
 */
class Error_404 extends Controller{
    
    public function __construct($cause){
        $page_error = true;//不存在该页面
        require_once (VIEWS_PATH."/404.php");
    }
}