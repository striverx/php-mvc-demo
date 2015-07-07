<?php
/*+++++++++++++++++++++++++++++++++++++
 * 数据库操作基类  定义基本操作 
 * 所需参数在项目中用常量定义
 * 抽象类 不能直接实例化
 * 2014-01-17 xiaofeng
 *++++++++++++++++++++++++++++++++++++*/
	abstract class Db{
		// 表主键
		public 	   $pk;
		// 最后操作的SQL语句
		public     $lastSql;
		// 最后插入表的数据ID
		public     $lastInsId;
		// 表
		public     $db_table;
		// 数据库类型 默认为mysql
		protected  $db_type;
		// 数据库主机
		protected  $db_host;
		// 数据库端口 默认为 3306
		protected  $db_port;
		// 数据库用户名
		protected  $db_user;
		// 数据库密码
		protected  $db_pwd;
		// 数据库名
		protected  $db_name;
		// 数据库字符集 默认为 UTF8
		protected  $db_charset;
		// 表字段 自动设置
		public     $fields;
		// 常用操作组合处理
		protected  $db_sql = array('field'=>'','where'=>'','order'=>'','limit'=>'');
		
		/**
		 * [__construct 配置数据库 调用连接方法 自动设置表字段方法]
		 * @param [type] $tableName [表名 必选]
		 */
		public function __construct($tableName){

			$this->db_host    = DB_HOST;
			$this->db_user    = DB_USER;
			$this->db_pwd     = DB_PWD;
			$this->db_name    = DB_NAME;
			$this->db_type    = defined('DB_TYPE')?DB_TYPE:'mysql';
			$this->db_port    = defined('DB_PORT')?DB_PORT:3306;
			$this->db_charset = defined('DB_CHARSET')?DB_CHARSET:'UTF8';
			$this->db_table   = defined('DB_PREFIX')?DB_PREFIX.strtolower($tableName):strtolower($tableName);			
			$this->connect();
			$this->setFields();

		}
		
		/**
		 * [setFields 获取表字段 主键]
		 */
		protected function setFields(){
			$sql = "DESC ".$this->db_table;
			$res = $this->query($sql,'desc');
			$fields = ' ';
			foreach($res as $val){
				if($val['Key']=='PRI') $this->pk = $val['Field'];
				$fields .= $val['Field'].','; 
			}
			
			$this->fields = rtrim($fields,',');
			$this->field();
			
		}
		
		/**
		 * [select 查询方法]
		 * @param  string $id [主键ID 可选]
		 * @return [type]     [array]
		 */
		public function select($id='',$use_mem=true){
			return $this->_query('select',$id,$use_mem);
		}
		
		/**
		 * [delete 删除方法]
		 * @param  string $id [主键ID 可选]
		 * @return [type]     [description]
		 */
		public function delete($id=''){
			return $this->_query('delete',$id);
		}
		
		/**
		 * [_query 组合SQL语句 并调用query方法执行 query方法需要被实现]
		 * @param  [type] $method [方法类型]
		 * @param  string $id     [主键ID 可选]
		 * @return [type]         [array]
		 */
		protected function _query($method,$id='',$use_mem=true){
		
			if(is_int($id)){	
				if(empty($this->db_sql['where'])){
					$this->db_sql['where'] = ' WHERE '.$this->pk.'='.$id;
				}else{
					$this->db_sql['where'] .= ' AND '.$this->pk.'='.$id;
				}
			}elseif(!empty($id)){
				$this->exception('该参数可选，但只能是整数！');
			}
			
			$sql = $method.' '.($method=='select'?$this->db_sql['field']:'').' FROM '.$this->db_table.$this->parseSql();

			/* 是否使用memcache */
			if($method=='select' && defined('MEMCACHE') && MEMCACHE===true && $use_mem){
				$mem  = new MemcacheModel;
				if($mem->check_connect()){
					$data = $mem->getCache($sql);
					if($data){
						return $data;
					}else{
						$result = $this->query($sql,$method);
						$expire = defined('MEM_EXPIRE')?MEM_EXPIRE:180;
						$mem->addCache($sql,$result,$expire);
						return $result;
					}
				}
			}

			return $this->query($sql,$method);

		}
		
		/**
		 * [update 更新]
		 * @param  [type] $param [数据数组]
		 * @return [type]        [description]
		 */
		public function update($param){
			
			if(!is_array($param)) $this->exception('参数必须是数组！！');
		
			$value = ' ';
			foreach($param as $key=>$val){
				$value .= $key."='".$val."',"; 
			}
			$sql = "UPDATE ".$this->db_table." SET ".rtrim($value,',').$this->parseSql();
			return $this->query($sql,'update');
			
		}

		public function count(){

			$sql = "SELECT count(*) FROM ".$this->db_table.$this->parseSql();
			$result = $this->query($sql,'select');
			return $result['count(*)'];
		}

		public function find($id='',$use_mem=true){
			$result = $this->limit(1)->select($id,$use_mem);
			if(empty($result)){
				return false;
			}
			return $result[0];
		}

		
		/**
		 * [parseSql 组合SQL语句]
		 * @return [type] [description]
		 */
		protected function parseSql(){
			return $this->db_sql['where'].$this->db_sql['order'].$this->db_sql['limit'];
		}
		
		/**
		 * [where 组合WHERE语句 ]
		 * @param  [type] $param [数组 可以是一维或二维 $param['or'] 可改变多个条件间的关系]
		 * @return [type]        [description]
		 */
		public function where($param){

			if(empty($param)){ return $this;}
			
			$where = ' WHERE ';

			$flag = '';
			
			if(is_array($param)){

				$flag = isset($param['or'])?' OR ':' AND ';

				foreach($param as $key=>$val){
					if(is_array($val)){
						$where .= $key." ".$val[0]." ".$val[1]." ".$flag;
					}else{
						$where .= $key."='".$val."' ".$flag;
					}
				}
			}elseif(is_string($param)){
				$where .= $param;
			}else{
				$this->exception('参数格式错误！！！');
			}

			$this->db_sql['where'] = rtrim($where,$flag);
			
			return $this;
			
		}

		/**
		 * [__call description]
		 * @param  [type] $method [description]
		 * @param  [type] $param  [description]
		 * @return [type]         [description]
		 */
		public function __call($method,$param){
		
			switch($method){
				case 'order':
					$this->db_sql['order'] = empty($param)?'':' ORDER BY '.$param[0];
					break;
				case 'limit':
					$this->db_sql['limit'] = empty($param[0])?'':' LIMIT '.$param[0];
					break;		
				case 'field':
					$this->db_sql['field'] = empty($param)?$this->fields:$param[0];
					break;		
				default : $this->exception(__CLASS__.'类中中不存在'.$method.'方法！'); break;
					
			}
			return $this;
			
		}
		
		/**
		 * [exception 抛出异常方法 可重写]
		 * @param  [type] $msg [description]
		 * @return [type]      [description]
		 */
		protected function exception($msg){
			exit($msg.'<br />SQL:'.$this->lastSql);
		}
		

		/* 数据库连接方法 */
		abstract protected function connect();
		/* 执行SQL语句方法 */
		abstract public function query($sql,$method);
		/* 添加数据方法 */
		abstract public function insert($param);
		/* 关闭数据库连接方法 */
		abstract protected function close();
		
		public function __destruct(){
			$this->close();
		}
		
		
	}
	
	
	
	

	
	
	
	