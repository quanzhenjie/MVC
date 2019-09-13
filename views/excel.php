<?php
    header("Pragma: public");
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Content-Type: application/force-download");
    header("Content-Type: application/octet-stream");
    header("content-type:text/html; charset=UTF-8");
    header("Content-Type: application/download");
    header("Content-type:application/vnd.ms-excel");
    header("Content-Disposition:attachment; filename=".$this->excel_file_name.".xls");
?>
<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">
<head>
    <meta http-equiv="expires" content="Mon, 06 Jan 1999 00:00:01 GMT">
    <meta http-equiv="Content-type" content="text/html;charset=UTF-8" />
    <!--[if gte mso 9]>
    <xml>
        <x:ExcelWorkbook>
        <x:ExcelWorksheets>
               <x:ExcelWorksheet>
               <x:Name></x:Name>
               <x:WorksheetOptions>
                       <x:DisplayGridlines/>
               </x:WorksheetOptions>
               </x:ExcelWorksheet>
       </x:ExcelWorksheets>
       </x:ExcelWorkbook>
    </xml>
    <!--[endif]-->
</head>
<body>
    <div align=center x:publishsource="Excel">
        <table>
            <?php
                echo "<tr>";
                foreach($this->fields as $val){
                    echo "<th style='vnd.ms-excel.numberformat:@'>".$val."</th>";
                }
                echo "</tr>";
                foreach($this->result_data as $result){
                    echo "<tr>";
                    foreach($this->fields as $key=>$val){
                        echo "<td style='vnd.ms-excel.numberformat:@'>".$result[$key]."</td>";
                    }
                    echo "</tr>";
                }
            ?>
        </table>
    </div>
</body>
</html>