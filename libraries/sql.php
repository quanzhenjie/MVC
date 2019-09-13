<?php
/**
 +----------------------------------------------------------
 * 集成新旧数据库操作类
 +----------------------------------------------------------
 * 文件名称  .php
 +----------------------------------------------------------
 * 文件描述  数据库操作类
 +----------------------------------------------------------
 * 作    者  全振杰
 +----------------------------------------------------------
 * 时    间  2018-11-12
 +----------------------------------------------------------
 */
 class Db{
    
    //操作数据库类型，默认MySQL
    public $SqlType = "mysql";
    //传统连接还是pdo，默认pdo，传统定为old,          php版本6以上不再支持传统连接全部采用pdo，根据实际情况来定
    public $LibType = "pdo";
    //数据库连接标识
    protected $link = null;
    //数据表前缀
    private $table_prefix   = "";
    //当前操作的表
    public    $table        = '';
    //数据库操作次数
    protected $queryCount = 0;
    //返回结果数据集
    protected $result;
    //查询参数
    protected $options_set = array("where"=>null,"cache"=>false,"cacheTime"=>60,"cacheName"=>null,"cachePath"=>null);
    protected $options = array();
    //当前执行的SQL语句
    public $sql          = '';
    //返回受上一个 SQL 语句影响的行数
    public $affectedRowNumber;
    //缓存次数
    protected $cacheCount   = 0;
    //缓存路径
    protected $cachePath    = '';
    //数据返回类型, 1代表数组, 2代表对象
    protected $returnType   = 1;
    
    //构造函数
    function __construct(){
        $this->options = $this->options_set;
    }
    
    /**
         * 连接数据库
         *
         * @access      public
         * @param       array    $db  数据库配置
         * @return      resource 数据库连接标识
         */
    public function connect($db){
        $this->SqlType = $db['type']=="mssql"?"mssql":"mysql";
        
        $this->table_prefix = $db['prefix'];
        $this->cachePath = isset($db['cachepath']) ? $db['cachepath'] : './';
        if($this->LibType == "pdo"){
            try{
                if($this->SqlType=="mssql"){
                    $db['host'] = isset($db['port']) ? $db['host'] . ',' . $db['port'] : $db['host'];
                    $this->link = new PDO("sqlsrv:Server=".$db['host'].";Database=".$db['database']."",$db['user'],$db['pwd']);
                }else{
                    $db['host'] = isset($db['port']) ? $db['host'] . ':' . $db['port'] : $db['host'];
                    $this->link = new PDO("mysql:host=".$db['host'].";dbname=".$db['database'].";charset=".$db['char'],$db['user'],$db['pwd']);
                }
                
                if($db['pconnect']){
                    $this->link->setAttribute(PDO::ATTR_PERSISTENT,true);
                }
                //设置抛出错误
                //$this->link->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
                //设置当字符串为空时转换为sql的null
                //$this->link->setAttribute(PDO::ATTR_ORACLE_NULLS,true);
                //由MySQL完成变量的转义处理
                //$this->link->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
                if($this->SqlType=="mysql"){
                    $this->link->exec("SET NAMES '{$db['char']}'");
                }
                return $this->link;
            }catch(PDOException $e){
                die('Could not connect to the database:<br/>' . $e);
            }
        }else{
            $db['host'] = isset($db['port']) ? $db['host'] . ':' . $db['port'] : $db['host'];
            //根据配置使用不同函数连接数据库
            $func = $db['pconnect'] ? $this->SqlType.'_pconnect' : $this->SqlType.'_connect';
            $this->link = $func($db['host'], $db['user'], $db['pwd']);
            if($this->SqlType=="mysql"){
                mysql_select_db($db['database'], $this->link);
                mysql_query("SET NAMES '{$db['char']}'");
            }else{
                mssql_select_db($db['database'], $this->link);
                ini_set ("mssql.datetimeconvert","0");
            }
            return $this->link;
        }
    }
    
    
    /**
         * 读取结果集中的所有记录到数组中
         *
         * @access public
         * @param  resource  $result  结果集
         * @return array
         */
    public function fetchAll($result = NULL)
    {
        $rows = array();
        while($row = $this->fetch($result))
        {
            $rows[] = $row;
        }
        return $rows;
    }

        //---------------------- 华丽分割线 ------------------------


    /**
         * 读取结果集中的一行记录到数组中
         *
         * @access public
         * @param  resource  $result  结果集
         * @param  int       $type    返回类型, 1为数组, 2为对象
         * @return mixed              根据返回类型返回
         */
    public function fetch($result = NULL, $type = NULL)
    {
        $result = is_null($result) ? $this->result : $result;
        $type   = is_null($type)   ? $this->returnType : $type;
        if($this->LibType == "pdo"){
            return $result->fetch($type === 1 ? PDO::FETCH_ASSOC:PDO::FETCH_OBJ);
        }else{
            if($this->SqlType=="mysql"){
                $func   = $type === 1 ? 'mysql_fetch_assoc' : 'mysql_fetch_object';
            }else{
                $func   = $type === 1 ? 'mssql_fetch_assoc' : 'mssql_fetch_object';
            }
            return $func($result);
        }
    }
    
    /**
        *自定义查询
        */
        public function custom_select($custom_sql,$all){
            $this->sql = $custom_sql;
            $result = $this->query();
            $row    = $all === TRUE ? $this->fetchAll($result) : $this->fetch($result);
            $this->options = $this->options_set;
            return $row;
        }
    
    /**
         * 查询符合条件的一条记录
         *
         * @access      public
         * @param       string    $where  查询条件
         * @param       string    $field  查询字段
         * @param       string    $table  表
         * @return      mixed             符合条件的记录
         */
    public function find($where = NULL, $field = '*', $table = '')
    {
        return $this->findAll($where = NULL, $field = '*', $table = '', FALSE);
    }
    
    /**
         * 查询符合条件的所有记录
         *
         * @access      public
         * @param       string    $where  查询条件
         * @param       string    $field  查询字段
         * @param       string    $table  表
         * @return      mixed             符合条件的记录
         */
    public function findAll($where = NULL, $field = '*', $table = '', $all = TRUE)
    {
        $this->options['where'] = is_null($where) ? $this->options['where'] : $where;
        $this->options['field'] = isset($this->options['field']) ? $this->options['field'] : $field;
        $this->options['table'] = $table == '' ?  $this->table : $table;
        $sql   = "SELECT {$this->options['field']} FROM {$this->options['table']} ";
        $sql  .= isset($this->options['join']) ? ' LEFT JOIN ' . $this->options['join'] : '';
        $sql  .= isset($this->options['where']) ? ' WHERE ' . $this->options['where'] : '';
        $sql  .= isset($this->options['group']) ? ' GROUP BY ' . $this->options['group'] : '';
        $sql  .= isset($this->options['having']) ? ' HAVING ' . $this->options['having'] : '';
        $sql  .= isset($this->options['order']) ? ' ORDER BY ' . $this->options['order'] : '';
        $sql  .= isset($this->options['limit']) ? ' LIMIT ' . $this->options['limit'] : '';
        $this->sql = $sql;
        $row    = NULL;
        
        //如果开启了缓存, 那么重缓存中获取数据
        if($this->options['cache'] === TRUE)
        {
            $row = $this->readCache();
        }

        //如果读取失败, 或则没有开启缓存
        if(is_null($row))
        {
            $result = $this->query();
            $row    = $all === TRUE ? $this->fetchAll($result) : $this->fetch($result);
            if($this->options['cache'] === TRUE)
            //如果开启了缓存, 那么就写入
            $this->writeCache($row);
            $this->options = $this->options_set;
        }

        return $row;
    }
    
    /**
         * 缓存当前查询
         *
         * @access      public
         * @param       string    $name   缓存名称
         * @param       int       $time   缓存有效时间, 默认为60秒
         * @param       string    $path   缓存文件存放路径
         * @return      object            数据库操作对象
         */
        public function cache($name = '', $time = 60, $path = '')
        {
                $this->options['cache']         = TRUE;
                $this->options['cacheTime']     = $time;
                $this->options['cacheName']     = empty($name) ? md5($this->sql) : $name;
                $this->options['cachePath']     = empty($path) ? $this->cachePath : $path;
                return $this;
        }


        //---------------------- 华丽分割线 ------------------------

        /**
         * 读取缓存
         *
         * @access      public
         * @return      mixed   如果读取成功返回缓存内容, 否则返回NULL
         */
        protected function readCache()
        {
                $file = $this->options['cachePath'] . $this->options['cacheName'] . '.php';
                if(file_exists($file))
                {
                        //缓存过期
                        if((filemtime($file) + $this->options['cacheTime']) < time())
                        {
                                @unlink($file);
                                return NULL;
                        }

                        if(1 === $this->returnType)
                        {
                                $row = include $file;
                        }
                        else
                        {
                                $data = file_get_contents($file);
                                $row  = unserialize($data);
                        }
                        return $row;
                }
                return NULL;
        }

        //---------------------- 华丽分割线 ------------------------

        /**
         * 写入缓存
         *
         * @access      public
         * @param       mixed   $data   缓存内容
         * @return      bool            是否写入成功
         */
        public function writeCache($data)
        {
                $this->cacheCount++;
                $file = $this->options['cachePath'] . $this->options['cacheName'] . '.php';
                if(1 === $this->returnType)
                        $data = '<?php return ' . var_export($data, TRUE) . '; ?>';
                else
                        $data = serialize($data);
                return file_put_contents($file, $data);
        }
    
    /**
         * 执行SQL命令
         *
         * @access      public
         * @param       string    $sql    SQL命令
         * @param       resource  $link   数据库连接标识
         * @return      mixed             数据库结果集
         */
    public function query($sql = '', $link = NULL){
        $this->queryCount++;
        $sql = empty($sql) ? $this->sql : $sql;
        $link = is_null($link) ? $this->link : $link;
        if($this->LibType == "pdo"){
            $this->result = $link->query($sql);
            return $this->result;
        }else{
            if($this->SqlType=="mysql"){
                $this->result = mysql_query($sql, $link);
            }else{
                $this->result = mssql_query($sql, $link);
            }
            if(is_resource($this->result))
            {
                return $this->result;
            }
            //如果执行SQL出现错误, 那么抛出异常
            exit('<strong>sql error:</strong>' . $this->getError());
        }
    }

        //---------------------- 华丽分割线 ------------------------

        /**
         * 执行SQL命令
         *
         * @access      public
         * @param       string    $sql    SQL命令
         * @param       resource  $link   数据库连接标识
         * @return      bool              是否执行成功
         */
        public function execute($sql = '', $link = NULL)
        {
            $this->queryCount++;
            $sql = empty($sql) ? $this->sql : $sql;
            $link = is_null($link) ? $this->link : $link;
            if($this->LibType == "pdo"){
                $result = $link->prepare($sql);
                $result->execute();
                $this->affectedRowNumber = $result->rowCount();
            }else{
                if($this->SqlType=="mysql"){
                    $result = mysql_query($sql, $link);
                }else{
                    $result = mssql_query($sql, $link);
                }
            }
            if($result){
                return TRUE;
            }else{
                return FALSE;
            }
        }
        
        /**
         * 插入记录
         *
         * @access public
         * @param  array  $data  插入的记录, 格式:array('字段名'=>'值', '字段名'=>'值');
         * @param  string $table 表名
         * @return bool          当前记录id
         */
        public function add($data, $table = NULL)
        {
                $table = is_null($table) ? $this->table : $table;
                $sql   = "INSERT INTO {$table}";
                $fields = $values = array();
                $field = $value = '';
                //遍历记录, 格式化字段名称与值
                foreach($data as $key => $val)
                {
                    $val = str_replace("'","''",$val);
                    if($val === null){
                        $fields[] = "{$table}.{$key}";
                        $values[] = "NULL";
                    }else{
                        $fields[] = "{$table}.{$key}";
                        $values[] = "'{$val}'";//is_numeric($val) ? $val : "'{$val}'";
                    }
                }
                $field = join(',', $fields);
                $value = join(',', $values);
                unset($fields, $values);
                $sql .= "({$field}) VALUES({$value})";
                $this->sql = $sql;
                $this->execute();
                return $this->insertId();
        }

        //---------------------- 华丽分割线 ------------------------

        /**
         * 删除记录
         *
         * @access public
         * @param  string  $where  条件
         * @param  string  $table  表名
         * @return bool            影响行数
         */
        public function delete($where = NULL, $table = NULL)
        {
                $table = is_null($table) ? $this->table : $table;
                $where = is_null($where) ? $this->options['where'] : $where;
                $sql   = "DELETE FROM {$table} WHERE {$where}";
                $this->sql = $sql;
                $this->execute();
                return $this->affectedRows();
        }

        //---------------------- 华丽分割线 ------------------------

        /**
         * 更新记录
         *
         * @access public
         * @param  array   $data   更新的数据 格式:array('字段名' => 值);
         * @param  string  $where  更新条件
         * @param  string  $table  表名
         * @return bool            影响多少条信息
         */
        public function update($data, $where = NULL, $table = NULL)
        {
                $table  = is_null($table) ? $this->table : $table;
                $where  = is_null($where) ? $this->options['where'] : $where;
                $sql    = "UPDATE {$table} SET ";
                $values = array();
                foreach($data as $key => $val)
                {
                    $val = str_replace("'","''",$val);
                    if($val === null){
                        $val      = "NULL";
                        $values[] = "{$table}.{$key} = {$val}";
                    }else{
                        $val      = "'{$val}'";//is_numeric($val) ? $val : "'{$val}'";
                        $values[] = "{$table}.{$key} = {$val}";
                    }
                }
                $value = join(',', $values);
                $this->sql = $sql . $value . " WHERE {$where}";
                $this->execute();
                return $this->affectedRows();

        }
        
        //自动加载函数, 实现特殊操作
        public function __call($func, $args)
        {
                if(in_array($func, array('field', 'join', 'where', 'order', 'group', 'limit', 'having')))
                {
                        $this->options[$func] = array_shift($args);
                        return $this;
                }
                elseif($func === 'table')
                {
                        $this->options['table'] = $this->table_prefix.array_shift($args);
                        $this->table            = $this->options['table'];
                        return $this;
                }
                //如果函数不存在, 则抛出异常
                exit('Call to undefined method Db::' . $func . '()');
        }
        
        //返回上一次操作所影响的行数
        public function affectedRows($link = null)
        {
            if($this->LibType == "pdo"){
                return $this->affectedRowNumber;
            }else{
                $link = is_null($link) ? $this->link : $link;
                return $this->SqlType=="mysql"?mysql_affected_rows($link):mssql_rows_affected($link);
            }
        }

        //返回上一次操作记录的id
        public function insertId($link = null)
        {
            $link = is_null($link) ? $this->link : $link;
            if($this->LibType == "pdo"){
                return $link->lastInsertId();
            }else{
                if($this->SqlType=="mysql"){
                    return mysql_insert_id($link);
                }else{
                    $result = @mssql_query("SELECT @@identity", $link);
                    if (!$result)
                    {
                        return -1;
                    }
                    return mssql_result($result, 0, 0);
                }
            }
        }
        
        //清空结果集
        public function free($result = null)
        {
                $result = is_null($result) ? $this->result : $result;
                return $this->SqlType=="mysql"?mysql_free_result($result):mysql_free_result($result);
        }


        //返回错误信息
        public function getError($link = NULL)
        {
                $link = is_null($link) ? $this->link : $link;
                return $this->SqlType=="mysql"?mysql_error($link):mysql_error($link);
        }

        //返回错误编号
        public function getErrno($link = NULL)
        {
                $link = is_null($link) ? $this->link : $link;
                return $this->SqlType=="mysql"?mysql_errno($link):mysql_errno($link);
        }
    
}