<?php
	/**
	 * 数据库抽象层PDO操作类 继承Db基类 
 	 * 2014-01-18 xiaofeng
	 */
	class PdoModel extends Db{
		
		/* PDO实例 句柄 */
		protected static $pdo = null;
		
		/**
		 * [connect description]
		 * @return [type] [description]
		 */
		protected function connect(){
			
			try{
				$dsn = $this->db_type.':dbname='.$this->db_name.';host='.$this->db_host.';port='.$this->db_port;
				$option = array(PDO::ATTR_PERSISTENT=>true,PDO::MYSQL_ATTR_USE_BUFFERED_QUERY=>true,PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION);
				self::$pdo = new PDO($dsn,$this->db_user,$this->db_pwd,$option);
				self::$pdo->query('SET NAMES '.$this->db_charset);	
			}catch(PDOException $e){	
				$this->exception($e->getMessage());
			}	
				
				
		}
		
		public function query($sql,$method=''){
			
			if(!is_string($sql) || !is_string($method)){
				$this->exception('参数必须是字符串！！！');
			}
			
			$this->lastSql = $sql;
			
			if($method==''){
				$temp = explode(' ',ltrim($sql));
				$method = strtolower(array_shift($temp));
			}
			
			$statement = self::$pdo->prepare($sql);
			try{
				$statement->execute();
			}catch(PDOException $e){
				$this->exception($e->getMessage());
			}	
			

			switch($method){
				case 'select':
				case 'desc':
					$result = $statement->fetchAll(PDO::FETCH_ASSOC);
				break;
				case 'delete':
				case 'update';
					$result = $statement->rowCount();
				break;

			}	
			
			// if(is_array($result) && count($result)==1){
			// 	$result = array_pop($result);
			// }

			return $result;

		}
		
		public function insert($param){
		
			if(!is_array($param)) $this->exception('参数必须是数组！！！');
			
			$place = $fields = '';
			$value = array();

			if(!is_array(current($param))){
				$new_param[] = $param;
			}else{
				$new_param = $param;
			}
			
			foreach($new_param[0] as $key=>$val){
				if(empty($val)) continue;
					$fields .= $key.',';
					$place  .= '?,';
			}
			foreach($new_param as $val){
				$temp = array();
				foreach($val as $key=>$val){
					if(empty($val)) continue;
					$temp[] = $val;
				}
				$value[] = $temp;				
			}
			
			$this->lastSql =  $sql = "INSERT INTO ".$this->db_table."(".rtrim($fields,',').") VALUES(".rtrim($place,',').")";

			$statement = self::$pdo->prepare($sql);
			
			try{
				foreach($value as $val){
					$statement->execute($val);
				}	
					
			}catch(PDOException $e){
				$this->exception($e->getMessage());
			}
			
			$this->lastInsId = self::$pdo->lastInsertId();
			
			return $this->lastInsId;
			
		}
		
		
		protected function close(){
			if(!is_null(self::$pdo)){
				self::$pdo = null;
			}
		}
		
		
	}