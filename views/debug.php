<html>
<head>
    <style>
        .debug{
            width: 100%;
            height: 200px;
            background: #333;
            clear: both;
            position: relative;
            left: 0px;
            bottom: 0px;
            overflow-y: scroll;
        }
        .debug .debug_title{
            margin: 5px;
            color: red;
        }
        .debug .debug_id{
            width: 25px;
            font-size: large;
            font-weight: bold;
            color: #EFEFEF;
        }
        .debug .debug_sql{
            font-size: 10px;
            font-family: cursive;
            color: #FFF000;
        }
        .debug .debug_list{
            border-bottom: 1px solid #30C0FF;
        }
        .debug .debug_note{
            font-size: 12px;
            color: #FF00FF;
        }
    </style>
</head>
<body>
    <div id="debug" class="debug">
        <h3 class="debug_title">调试界面</h3>
        <?php
        /**********实例***************
        <div class="debug_list"><table><tr><th rowspan="2" class="debug_id">1</th><td class="debug_sql">SELECT * FROM DEBUG</td></tr><tr><td class="debug_note">查询DEBUG实例</td></tr></table></div>
        */
        $debug_id = 0;
        foreach($this->debug as $debug_list){
        ?>
        <div class="debug_list"><table><tr><th rowspan="2" class="debug_id"><?php echo ++$debug_id; ?></th><td class="debug_sql"><?php echo $debug_list['sql']; ?></td></tr><tr><td class="debug_note"><?php echo $debug_list['note']; ?></td></tr></table></div>
        <?php
        }
        
        ?>
    </div>
</body>
</html>