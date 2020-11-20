<?php
class CreateForm {
    
    private $fields = array();//表单所有字段，索引代表数据库字段名，值为数组
    private $data = array();//列表数据，数据库取得的2维数组
    private $buttons = array();//表单的操作按钮
    private $list_fields = array();//表单列表字段
    private $list_data = array();//表单列表数据
    private $base_path = "";//基础路径
    /**
     *  @param      array           $fields                     表单所有字段
     *  @param      array           $data                       数据库数据库取得的数组
     *  @param      array           $buttons                    表单的操作按钮
    **/ 
    function __construct($fields,$data,$buttons=array(),$list_fields=array(),$list_data=array(array(),array(),array())){
        $this->fields = $fields;
        $this->data = $data;
        $this->buttons = $buttons;
        $this->list_fields = $list_fields;
        $this->list_data = $list_data;
        $this->base_path = THEMES_PATH?THEMES_PATH."/":"";
    }
    
    public function c_form(){
        $form_html = "<table width=\"100%\">";
        $i = 0;
        foreach($this->fields as $key=>$arr){
            $i++;
            if($i%2){
                $form_html .= "<tr>";
            }
            
            $attrs = array("colspan","value","onclick","onchange","onblur","onkeydown","disabled","required","separate");
            for($aa=0;$aa<count($attrs);$aa++){
                $arr[$attrs[$aa]] = isset($arr[$attrs[$aa]])?$arr[$attrs[$aa]]:null;
            }
            
            switch($arr['type']){
                case "null":
                    $this->data[$key] = isset($this->data[$key])?$this->data[$key]:"";
                    $form_html .= "<th><label>".$arr['label']."</label></th><td".($arr['colspan']>1?" colspan=\"3\"":"").">".($arr['value'] && !$this->data[$key]?$arr['value']:$this->data[$key])."</td>";
                    break;
                case "text":
                    $form_html .= "<th><label>".$arr['label']."</label></th><td".($arr['colspan']>1?" colspan=\"3\"":"")."><input type=\"text\" id=\"".$key."\" value=\"".(isset($this->data[$key])?$this->data[$key]:$arr['value'])."\"".($arr['onclick']?" onclick=\"".$arr['onclick']."\"":"").($arr['onblur']?" onblur=\"".$arr['onblur']."\"":"").($arr['onkeydown']?" onkeydown=\"".$arr['onkeydown']."\"":"").($arr['disabled']?" disabled=\"disabled\"":"")." />".($arr['required']?"<font color='red'>*</font>":"")."</td>";
                    break;
                case "password":
                    $form_html .= "<th><label>".$arr['label']."</label></th><td".($arr['colspan']>1?" colspan=\"3\"":"")."><input type=\"password\" id=\"".$key."\" value=\"".(isset($this->data[$key])?$this->data[$key]:$arr['value'])."\"".($arr['onclick']?" onclick=\"".$arr['onclick']."\"":"").($arr['onblur']?" onblur=\"".$arr['onblur']."\"":"").($arr['onkeydown']?" onkeydown=\"".$arr['onkeydown']."\"":"")." />".($arr['required']?"<font color='red'>*</font>":"")."</td>";
                    break;
                case "radio":
                    $form_html .= "<th><label>".$arr['label']."</label></th><td".($arr['colspan']>1?" colspan=\"3\"":"").">";
                    foreach($arr['options'] as $option){
                        $option['checked'] = isset($option['checked'])?$option['checked']:false;
                        $option['value'] = isset($option['value'])?$option['value']:"";
                        $option['text'] = isset($option['text'])?$option['text']:"";
                        $this->data[$key] = isset($this->data[$key])?$this->data[$key]:"";
                        $form_html .= "<input type=\"radio\" name=\"".$key."\" value=\"".$option['value']."\"".(($option['checked'] && !$this->data[$key]) || $option['value']==$this->data[$key]?" checked=\"checked\"":"")." />".$option['text']." ";
                    }
                    $form_html .= ($arr['required']?"<font color='red'>*</font>":"")."</td>";
                    break;
                case "checkbox":
                    $form_html .= "<th><label>".$arr['label']."</label></th><td".($arr['colspan']>1?" colspan=\"3\"":"").">";
                    foreach($arr['options'] as $option){
                        $option['checked'] = isset($option['checked'])?$option['checked']:false;
                        $option['value'] = isset($option['value'])?$option['value']:"";
                        $option['text'] = isset($option['text'])?$option['text']:"";
                        $this->data[$key] = isset($this->data[$key])?$this->data[$key]:"";
                        $form_html .= "<input type=\"checkbox\" name=\"".$key."\" value=\"".$option['value']."\"".(($option['checked'] && !$this->data[$key]) || preg_match("@".$option['value']."@",$this->data[$key])?" checked=\"checked\"":"")." />".$option['text']." ";
                    }
                    $form_html .= ($arr['required']?"<font color='red'>*</font>":"")."</td>";
                    break;
                case "select":
                    $form_html .= "<th><label>".$arr['label']."</label></th><td".($arr['colspan']>1?" colspan=\"3\"":"")."><select id=\"".$key."\"".($arr['multiple']?" multiple=\"multiple\"":"").($arr['onchange']?" onchange=\"".$arr['onchange']."\"":"").">";
                    foreach($arr['options'] as $option){
                        $option['checked'] = isset($option['checked'])?$option['checked']:false;
                        $option['value'] = isset($option['value'])?$option['value']:"";
                        $option['text'] = isset($option['text'])?$option['text']:"";
                        $this->data[$key] = isset($this->data[$key])?$this->data[$key]:"";
                        $form_html .= "<option value=\"".$option['value']."\"".(($option['selected'] && !$this->data[$key]) || in_array($option['value'],explode($arr['separate']?$arr['separate']:"|",$this->data[$key]))?" selected=\"selected\"":"").">".$option['text']."</option>";
                    }
                    $form_html .= "</select>".($arr['required']?"<font color='red'>*</font>":"")."</td>";
                    break;
                default :
                    break;
            }
            if($arr['colspan']>1){
                $i++;
            }
            if(!$i%2){
                $form_html .= "</tr>";
            }
        }
        $form_html .= "</table>";
        return $form_html;
    }
    
    public function c_list($list_fields=array(),$list_data=array()){
        $this->list_fields = count($list_fields)?$list_fields:$this->list_fields;
        $this->list_data = count($list_data)?$list_data:$this->list_data;
        $total_row_number = count($this->list_data)>=1?count($this->list_data):3;
        $add_row_js_function = "<script type=\"text/javascript\">";
        $add_row_js_function .= "var form_list = {total_row_number : ".$total_row_number."};function delete_list_row(row_number){\$(\"#row_\"+row_number).remove();var row_object = \$(\"#row_\"+(row_number+1)+\" :input:enabled,#row_\"+(row_number+1)+\" select\");for(var i=Number(row_number+1);i<=form_list.total_row_number;i++){row_object = \$(\"#row_\"+i+\" :input,#row_\"+i+\" select\");\$(\"#xuhao_\"+i).text(Number(i-1));\$(\"#xuhao_\"+i).attr('id',\"xuhao_\"+Number(i-1));\$(\"#delete_\"+i).attr('href','javascript:delete_list_row(Number('+i+'-1));');\$(\"#delete_\"+i).attr('id',\"delete_\"+Number(i-1));for(var j=0;j<row_object.length;j++){if(row_object[j].id){row_object[j].id=row_object[j].id.replace(/_\d$/ig,'_'+Number(i-1));}else{row_object[j].name=row_object[j].name.replace(/_\d$/ig,'_'+Number(i-1));}}\$(\"#row_\"+i).attr('id','row_'+Number(i-1));}form_list.total_row_number--;}\n";
        $list_html = "";
        $list_html .= "<table width='100%' class='list_table'><tr><th>行数</th>";
        $add_row_js_function .= "function add_list_row(){form_list.total_row_number++;\$(\"#add_list_row_button\").before(\"<tr id='row_\"+form_list.total_row_number+\"'><td><span id='xuhao_\"+form_list.total_row_number+\"'>\"+form_list.total_row_number+\"</span></td>";
        $colspan = 0;
        foreach($this->list_fields as $key=>$list_field){
            if($list_field['type'] == "hidden"){
                continue;
            }
            
            $attrs = array("colspan","width","value","onclick","onchange","onblur","onkeydown","disabled","required","separate");
            for($aa=0;$aa<count($attrs);$aa++){
                $list_field[$attrs[$aa]] = isset($list_field[$attrs[$aa]])?$list_field[$attrs[$aa]]:null;
            }
            
            $colspan++;
            $list_html .= "<th".($list_field['colspan']>1?" colspan=\"".$list_field['colspan']."\"":"").">".$list_field['label']."</th>";
            switch($list_field['type']){
                case "null":
                    $add_row_js_function .= "<td".($list_field['colspan']>1?" colspan='".$list_field['colspan']."'":"").">".$list_field['value']."</td>";
                    break;
                case "text":
                    $add_row_js_function .= "<td".($list_field['colspan']>1?" colspan='".$list_field['colspan']."'":"")."><input type='text' style='width:".$list_field['width']."px;' id='".$key."_\"+form_list.total_row_number+\"'".($list_field['onclick']?" onclick=\\\"".$list_field['onclick']."\\\"":"").($list_field['onblur']?" onblur=\\\"".$list_field['onblur']."\\\"":"").($list_field['onkeydown']?" onkeydown=\\\"".$list_field['onkeydown']."\\\"":"").($list_field['disabled']?" disabled=\\\"disabled\\\"":"")." /></td>";
                    break;
                case "password":
                    $add_row_js_function .= "<td".($list_field['colspan']>1?" colspan='".$list_field['colspan']."'":"")."><input type='password' id='".$key."_\"+form_list.total_row_number+\"'".($list_field['onclick']?" onclick=\\\"".$list_field['onclick']."\\\"":"").($list_field['onblur']?" onblur=\\\"".$list_field['onblur']."\\\"":"").($list_field['onkeydown']?" onkeydown=\\\"".$list_field['onkeydown']."\\\"":"")." /></td>";
                    break;
                case "radio":
                    $add_row_js_function .= "<td".($list_field['colspan']>1?" colspan=\\\"".$list_field['colspan']."\\\"":"").">";
                    foreach($list_field['options'] as $option){
                        $add_row_js_function .= "<input type=\\\"radio\\\" name=\\\"".$key."_\"+form_list.total_row_number+\"\\\" value=\\\"".$option['value']."\\\"".($option['checked']?" checked=\\\"checked\\\"":"")." />".$option['text']." ";
                    }
                    $add_row_js_function .= "</td>";
                    break;
                case "checkbox":
                    $add_row_js_function .= "<td".($list_field['colspan']>1?" colspan=\\\"".$list_field['colspan']."\\\"":"").">";
                    foreach($list_field['options'] as $option){
                        $add_row_js_function .= "<input type=\\\"checkbox\\\" name=\\\"".$key."_\"+form_list.total_row_number+\"\\\" value=\\\"".$option['value']."\\\"".($option['checked']?" checked=\\\"checked\\\"":"")." />".$option['text']." ";
                    }
                    $add_row_js_function .= "</td>";
                    break;
                case "select":
                    $add_row_js_function .= "<td".($list_field['colspan']>1?" colspan=\\\"".$list_field['colspan']."\\\"":"")."><select id=\\\"".$key."_\"+form_list.total_row_number+\"\\\"".($list_field['multiple']?" multiple=\\\"multiple\\\"":"").($list_field['onchange']?" onchange=\\\"".$list_field['onchange']."\\\"":"").">";
                    foreach($list_field['options'] as $option){
                        $add_row_js_function .= "<option value=\\\"".$option['value']."\\\"".($option['selected']?" selected=\\\"selected\\\"":"").">".$option['text']."</option>";
                    }
                    $add_row_js_function .= "</select></td>";
                    break;
                default :
                    break;
            }
        }
        $add_row_js_function .= "<td><a id='delete_\"+form_list.total_row_number+\"' href='javascript:delete_list_row(\"+form_list.total_row_number+\");'><img src='".$this->base_path."images/error.gif' /></a></td></tr>\");}</script>\n";
        $list_html .= "<th>删除行</th></tr>";
        $row_num = 0;
        foreach($this->list_data as $data){
            $row_num++;
            $list_html .= "<tr id=\"row_".$row_num."\"><td><span id=\"xuhao_".$row_num."\">".$row_num."</span></td>";
            foreach($this->list_fields as $key=>$arr){
                
                for($aa=0;$aa<count($attrs);$aa++){
                    $arr[$attrs[$aa]] = isset($arr[$attrs[$aa]])?$arr[$attrs[$aa]]:null;
                }
                $data[$key] = isset($data[$key])?$data[$key]:"";
                switch($arr['type']){
                    case "null":
                        $list_html .= "<td".($arr['colspan']>1?" colspan=\"".$arr['colspan']."\"":"").">".$data[$key]."</td>";
                        break;
                    case "text":
                        $list_html .= "<td".($arr['colspan']>1?" colspan=\"".$arr['colspan']."\"":"")."><input type=\"text\" style='width:".$arr['width']."px;' id=\"".$key."_".$row_num."\" value=\"".$data[$key]."\"".($arr['onclick']?" onclick=\"".$arr['onclick']."\"":"").($arr['onblur']?" onblur=\"".$arr['onblur']."\"":"").($arr['onkeydown']?" onkeydown=\"".$arr['onkeydown']."\"":"").($arr['disabled']?" disabled=\"disabled\"":"")." /></td>";
                        break;
                    case "password":
                        $list_html .= "<td".($arr['colspan']>1?" colspan=\"".$arr['colspan']."\"":"")."><input type=\"password\" id=\"".$key."_".$row_num."\" value=\"".$data[$key]."\"".($arr['onclick']?" onclick=\"".$arr['onclick']."\"":"").($arr['onblur']?" onblur=\"".$arr['onblur']."\"":"").($arr['onkeydown']?" onkeydown=\"".$arr['onkeydown']."\"":"")." /></td>";
                        break;
                    case "radio":
                        $list_html .= "<td".($arr['colspan']>1?" colspan=\"".$arr['colspan']."\"":"").">";
                        foreach($arr['options'] as $option){
                            $option['checked'] = isset($option['checked'])?$option['checked']:false;
                            $option['value'] = isset($option['value'])?$option['value']:"";
                            $option['text'] = isset($option['text'])?$option['text']:"";
                            $list_html .= "<input type=\"radio\" name=\"".$key."_".$row_num."\" value=\"".$option['value']."\"".(($option['checked'] && !$data[$key]) || $option['value']==$data[$key]?" checked=\"checked\"":"")." />".$option['text']." ";
                        }
                        $list_html .= "</td>";
                        break;
                    case "checkbox":
                        $list_html .= "<td".($arr['colspan']>1?" colspan=\"".$arr['colspan']."\"":"").">";
                        foreach($arr['options'] as $option){
                            $option['checked'] = isset($option['checked'])?$option['checked']:false;
                            $option['value'] = isset($option['value'])?$option['value']:"";
                            $option['text'] = isset($option['text'])?$option['text']:"";
                            $list_html .= "<input type=\"checkbox\" name=\"".$key."_".$row_num."\" value=\"".$option['value']."\"".(($option['checked'] && !$data[$key]) || preg_match("@".$option['value']."@",$data[$key])?" checked=\"checked\"":"")." />".$option['text']." ";
                        }
                        $list_html .= "</td>";
                        break;
                    case "select":
                        $list_html .= "<td".($arr['colspan']>1?" colspan=\"".$arr['colspan']."\"":"")."><select id=\"".$key."_".$row_num."\"".($arr['multiple']?" multiple=\"multiple\"":"").($arr['onchange']?" onchange=\"".$arr['onchange']."\"":"").">";
                        foreach($arr['options'] as $option){
                            $option['checked'] = isset($option['checked'])?$option['checked']:false;
                            $option['value'] = isset($option['value'])?$option['value']:"";
                            $option['text'] = isset($option['text'])?$option['text']:"";
                            $list_html .= "<option value=\"".$option['value']."\"".(($option['selected'] && !$data[$key]) || in_array($option['value'],explode($arr['separate']?$arr['separate']:"|",$data[$key]))?" selected=\"selected\"":"").">".$option['text']."</option>";
                        }
                        $list_html .= "</select></td>";
                        break;
                    default :
                        break;
                }
            }
            $list_html .= "<td><a id=\"delete_".$row_num."\" href=\"javascript:delete_list_row(".$row_num.");\"><img src=\"".$this->base_path."images/error.gif\" /></a></td></tr>";
        }
        $list_html .= "<tr id=\"add_list_row_button\"><td colspan=\"".($colspan+2)."\" style=\"background:#FFFF00;cursor: pointer;font-weight: bold;\" onclick=\"add_list_row()\">新增行数</td></tr></table>";
        return $add_row_js_function.$list_html;
    }
    
    public function c_buttons($buttons=null){
        $this->buttons = $buttons?$buttons:$this->buttons;
        $buttons_html = "&nbsp;";
        foreach($this->buttons as $button){
            $buttons_html .= "<input type=\"button\" value=\"".$button['value']."\"".(isset($button['id'])?" id=\"".$button['id']."\"":"")." onclick=\"".$button['onclick']."\" /> ";
        }
        $buttons_html .= "<span id=\"warn\" style=\"color: red;\"></span>";
        return $buttons_html;
    }
    
    public function c_js_check($fields=null,$list_fields=null){
        $fields = $fields?$fields:$this->fields;
        $list_fields = $list_fields?$list_fields:$this->list_fields;
        $js_check = "";
        foreach($fields as $key=>$val){
            $val['required'] = isset($val['required'])?$val['required']:false;
            if($val['required'] && ($val['type']=="text" || $val['type']=="password" || $val['type']=="radio" || ($val['type']=="select" && !$val['multiple']))){
                $js_check .= "if(".($val['type']=="radio"?"\$(\"input[name='".$key."']:radio:checked\")":"\$(\"#".$key."\").val()").".length<1){\$('#warn').html('”".$val['label']."“项不能为空');return false;}\n";
            }else if($val['type']=="checkbox"){
                $js_check .= "var ".$key."_checked_array = [];\n";
                $js_check .= "var ".$key."_checked_object = \$(\"input[name='".$key."']:checkbox:checked\");\n";
                $js_check .= "for(var i=0;i<".$key."_checked_object.length;i++){".$key."_checked_array[i] = ".$key."_checked_object[i].value;}\n";
            }else if($val['type']=="select" && $val['multiple']){
                $js_check .= "var ".$key."_selected_array = [];\n";
                $js_check .= "var ".$key."_selected_object = \$(\"#".$key." option:selected\");\n";
                $js_check .= "for(var i=0;i<".$key."_selected_object.length;i++){".$key."_selected_array[i] = ".$key."_selected_object[i].value;}\n";
            }
        }
        if($list_fields){
            $js_check .= "var list_checked_array = [];\n";
            $js_check .= "var list_checked_object = [];\n";
            $js_check .= "var list_selected_array = [];\n";
            $js_check .= "var list_selected_object = [];\n";
            $js_check .= "for(var i=1;i<=form_list.total_row_number;i++){\n";
            foreach($list_fields as $key=>$val){
                $val['required'] = isset($val['required'])?$val['required']:false;
                if($val['required'] && ($val['type']=="text" || $val['type']=="password" || $val['type']=="radio" || ($val['type']=="select" && !$val['multiple']))){
                    $js_check .= "if(".($val['type']=="radio"?"\$(\"input[name='".$key."_\"+i+\"']:radio:checked\")":"\$(\"#".$key."_\"+i).val()").".length<1){\$('#warn').html('第'+i+'行”".$val['label']."“项不能为空');return false;}\n";
                }else if($val['type']=="checkbox"){
                    $js_check .= "list_checked_array['".$key."_'+i] = [];\n";
                    $js_check .= "list_checked_object['".$key."_'+i] = \$(\"input[name='".$key."_\"+i+\"']:checkbox:checked\");\n";
                    $js_check .= "for(var j=0;j<list_checked_object['".$key."_'+i].length;j++){list_checked_array['".$key."_'+i][j] = list_checked_object['".$key."_'+i][j].value;}\n";
                }else if($val['type']=="select" && $val['multiple']){
                    $js_check .= "list_selected_array['".$key."_'+i] = [];\n";
                    $js_check .= "list_selected_object['".$key."_'+i] = \$(\"#".$key."_\"+i+\" option:selected\");\n";
                    $js_check .= "for(var k=0;k<list_selected_object['".$key."_'+i].length;k++){list_selected_array['".$key."_'+i][k] = list_selected_object['".$key."_'+i][k].value;}\n";
                }
            }
            $js_check .= "}\n";
        }
        return $js_check;
    }
    
    public function c_post_send($fields=null){
        $fields = $fields?$fields:$this->fields;
        $post_send = "";
        $i=0;
        foreach($fields as $key=>$val){
            if(($val['type']!="null" && $val['type']!="hidden") || ($key=="id" && isset($_GET['id']))){
                $i++;
                if($i!=1){
                    $post_send .= ",";
                }
                switch($val['type']){
                    case "null":
                        $post_send .= "\"".$key."\":".$_GET['id'];
                        break;
                    case "radio":
                        $post_send .= "\"".$key."\":\$(\"input[name='".$key."']:radio:checked\").val()";
                        break;
                    case "checkbox":
                        $post_send .= "\"".$key."\":".$key."_checked_array.join(\"".$val['separate']."\")";
                        break;
                    case "select":
                        if(!$val['multiple']){
                            $post_send .= "\"".$key."\":\$(\"#".$key."\").val()";
                        }else{
                            $post_send .= "\"".$key."\":".$key."_selected_array.join(\"".$val['separate']."\")";
                        }
                        break;
                    default :
                        $post_send .= "\"".$key."\":\$(\"#".$key."\").val()";
                        break;
                }
            }
        }
        return $post_send;
    }
    
    public function c_js_list($pid="back_data.result",$action="list_insert",$list_fields=null){
        $list_fields = $list_fields?$list_fields:$this->list_fields;
        $js_list = "\$.ajaxSetup({async: false});\nfor(var i=1;i<=form_list.total_row_number;i++){\n\$.post(\"index.php?controller=\"+url_get(\"controller\")+\"&action=".$action."\",{";
        $post_send = "";
        $i=0;
        foreach($list_fields as $key=>$val){
            if($val['type']!="null"){
                $i++;
                if($i!=1){
                    $post_send .= ",";
                }
                switch($val['type']){
                    case "hidden":
                        $post_send .= "\"".$key."\":".$pid;
                        break;
                    case "radio":
                        $post_send .= "\"".$key."\":\$(\"input[name='".$key."_\"+i+\"']:radio:checked\").val()";
                        break;
                    case "checkbox":
                        $post_send .= "\"".$key."\":list_checked_array['".$key."_'+i].join(\"".$val['separate']."\")";
                        break;
                    case "select":
                        if(!$val['multiple']){
                            $post_send .= "\"".$key."\":\$(\"#".$key."_\"+i).val()";
                        }else{
                            $post_send .= "\"".$key."\":list_selected_array['".$key."_'+i].join(\"".$val['separate']."\")";
                        }
                        break;
                    default :
                        $post_send .= "\"".$key."\":\$(\"#".$key."_\"+i).val()";
                        break;
                }
            }
        }
        $js_list .= $post_send;
        $js_list .= "},function(back_data){show_debug(back_data.debug);},\"json\");\n}\n\$.ajaxSetup({async: true});\n";
        return $js_list;
    }
}
?>