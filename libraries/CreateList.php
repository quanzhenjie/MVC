<?php
class CreateList {
    
    private $fields = array();//所有字段，索引代表数据库字段名，值代表字段的含义
    private $data = array();//列表数据，数据库取得的2维数组
    private $page_info = array();//分页数据信息，记录着总记录数，总页数，当前页数
    private $advanced_search_fields =array();//可指定用于高级搜索的字段，必须小于等于$fields,索引相同,值为字段数据类型，例如：number,string,date，默认为$fields
    private $list_operate = array();//列表页的特殊操作功能
    private $base_path = "";//基础路径
    /**
     *  @param      array           $fields                     数据库所有字段
     *  @param      array           $data                       数据库数据库取得的2维数组
     *  @param      array           $advanced_search_fields     用于高级搜索的字段
     *  @param      array           $list_operate               列表页的特殊操作功能
    **/ 
    
    function __construct($fields,$data,$page_info,$advanced_search_fields=null,$list_operate=null){
        $this->fields = $fields;
        $this->data = $data;
        $this->page_info = $page_info;
        if($advanced_search_fields){
            $this->advanced_search_fields = $advanced_search_fields;
        }else{
            foreach($fields as $key=>$val){
                $this->advanced_search_fields[$key] = "string";
            }
        }
        $this->list_operate = $list_operate?$list_operate:array("single_delete"=>null,"multiple_delete"=>null,"excel"=>null,"custom"=>array());
        $this->base_path = THEMES_PATH?THEMES_PATH."/":"";
    }
    
    public function c_order(){
        $order_html = "<select id=\"order_field\" onchange=\"order_field_change()\">";
        $order_html.= "<option value=''>==默认==</option>";
        $get_order = isset($_GET['order'])?explode(" ",$_GET['order']):null;
        foreach($this->fields as $key => $val){
            $order_html.= "<option value=\"".$key."\"".($get_order[0] == $key?"selected":"").">".$val."</option>";
        }
        $order_html.= "</select> <input type=\"button\" id=\"order_asc\" value=\"升序\"".($get_order[1]=="asc" || $get_order==null?" disabled=\"disabled\"":"")." onclick=\"order('asc')\" /> <input type=\"button\" id=\"order_desc\" value=\"倒序\"".($get_order[1]=="desc" || $get_order==null?" disabled=\"disabled\"":"")." onclick=\"order('desc')\" />";
        return $order_html;
    }
    
    public function c_search(){
        $search_html = "<select id=\"search_field\">";
        $get_where = isset($_GET['where'])?explode(" and ",stripslashes($_GET['where'])):array();
        $search_field = count($get_where)==1?explode(" like ",$get_where[0]):array(null,null);
        foreach($this->fields as $key => $val){
            $search_html.= "<option value=\"".$key."\"".($search_field[0] == $key?"selected":"").">".$val."</option>";
        }
        $search_html .= "</select>";
        $search_html .= "<input id=\"search_keyword\" type=\"text\" onkeydown=\"press_enter(event,this)\" value=\"".preg_replace("@'%|%'@","",$search_field[1])."\" />";
        $search_html .= "&nbsp;<input type=\"button\" value=\"搜索\" onclick=\"search()\" />";
        return $search_html;
    }
    
    public function c_advanced_search(){
        $advanced_search_html = '<div class="advanced_search_form"><div id="advanced_search_form_container" class="advanced_search_form_container"><table cellspacing="0"><tr>';
        $get_where = isset($_GET['where'])?stripslashes($_GET['where']):null;
        $i = 0;
        foreach($this->advanced_search_fields as $key => $val){
            $i++;
            if($val=="date"){
                preg_match("@".$key." between '([\d-]*?)' and '([\d-]*?)'@",$get_where,$matches);
                $matches[2] = isset($matches[2])?$matches[2]:null;
                $matches[2] = $matches[2] == "3000-01-01"?"":$matches[2];
            }else{
                preg_match("@".$key." like '%([^'%]+?)%'@",$get_where,$matches);
            }
            $matches[1] = isset($matches[1])?$matches[1]:null;$matches[2] = isset($matches[2])?$matches[2]:null;
            $advanced_search_html .= "<th>".$this->fields[$key].":</th><td>".($val=="date"?"<input type=\"text\" id=\"".$key."_start\" name=\"".$key."\" class=\"advanced_search_form_date\" onclick=\"WdatePicker()\" value=\"".$matches[1]."\" />-<input type=\"text\" id=\"".$key."_end\" name=\"".$key."\" class=\"advanced_search_form_date\" onclick=\"WdatePicker()\" value=\"".$matches[2]."\" />":"<input type=\"text\" id=\"".$key."\"".($val=="number"?" onkeydown=\"number_check(event,this)\"":"")." value=\"".$matches[1]."\" />")."</td>";
            $advanced_search_html .= $i%3?"":"</tr><tr>";
        }
        $advanced_search_html .= ($i%3?'</tr><tr>':'').'<td colspan="6"><input class="advanced_search_button" type="button" value="高级搜索" onclick="advanced_search()" /></td></tr></table></div></div>';
        return $advanced_search_html;
    }
    
    public function c_table(){
        //表头
        $table_html = "<table cellspacing=\"0\"><tr class=\"table_head\"><td style=\"width:33px;\"><input type=\"checkbox\" onclick=\"all_select(this)\" /></td><td>管理</td>";
        foreach($this->fields as $val){
            $table_html .= "<td>".$val."</td>";
        }
        $table_html .= ($this->list_operate['single_delete']?"<td>删除</td>":"")."</tr>\n";
        //自定义分页查询》表身
        $if_operate = count($this->list_operate['custom'])<1 && !$this->list_operate['multiple_delete'] && !$this->list_operate['excel'];
        $i=0;
        foreach($this->data as $result){
            $i++;
            $table_html .= "<tr class=\"".($i%2?"table_body_1":"table_body_2")."\"><td><input type=\"checkbox\" name=\"default_id\" value=\"".$result['id']."\"".($if_operate?"disabled=\"disabled\"":"")." /></td><td><a href=\"javascript:view(".$result['id'].");\"><img src=\"".$this->base_path."images/edit.png\" /></a></td>";
            foreach($this->fields as $key=>$val){
                $table_html .= "<td>".$result[$key]."</td>";
            }
            $table_html .= ($this->list_operate['single_delete']?"<td><a href=\"javascript:single_delete(".$result['id'].");\"><img src=\"".$this->base_path."images/error.gif\" /></a></td>":"")."</tr>\n";
        }
        //表未
        $custom = "";
        foreach($this->list_operate['custom'] as $text=>$js_function){
            $custom .= " <a onclick=\"".$js_function."\">".$text."</a>";
        }
        $table_html .= "<tr class=\"table_foot\"><td colspan=\"".(count($this->fields)+3)."\"><div class=\"page_operate\"><span class=\"operate\">".($if_operate?"":"<img src=\"".$this->base_path."images/arrow_ltr.png\" /> ").($this->list_operate['multiple_delete']?"<a onclick=\"multiple_delete()\">选中项删除</a> ":"").($this->list_operate['excel']?"<a onclick=\"excel_table()\">EXCEL导出</a>":"").$custom."</span><span class=\"page\">总记录：".$this->page_info['totalrow']." &nbsp; 页：".$this->page_info['currentpage']."/".$this->page_info['totalpage']." &nbsp; <input type=\"text\" onkeydown=\"press_enter(event,this)\" class=\"goto_page\" id=\"goto_page\" /> <a  onclick=\"javascript:gotopage(".$this->page_info['totalpage'].");\"><img src=\"".$this->base_path."images/go.gif\" /></a> &nbsp; <a onclick=\"firstpage()\"><img src=\"".$this->base_path."images/first.gif\" /></a> <a onclick=\"prevpage()\"><img src=\"".$this->base_path."images/prev.gif\" /></a> <a onclick=\"nextpage(".$this->page_info['totalpage'].")\"><img src=\"".$this->base_path."images/next.gif\" /></a> <a onclick=\"endpage(".$this->page_info['totalpage'].")\"><img src=\"".$this->base_path."images/end.gif\" /></a></span></div></td></tr></table>\n";
        return $table_html;
    }
}
?>