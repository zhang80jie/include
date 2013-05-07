<?php
/**
 * PDO 简单封装
 * @author jiangjituo
 *
 */
class mysqlPDO extends PDO
{
	public function __construct($host,$username='root',$password='',$dbname='',$options=array())
	{
		$pos = strrpos($host,':');
		
		if($host=='localhost'){
			$dns = 'mysql:host=localhost;dbname='.$dbname;
		}elseif($host[0] == '/'){
			$dns = 'mysql:unix_socket='.$host.';dbname='.$dbname;
		}elseif($pos){
			$dns = 'mysql:host='.substr($host,0,$pos).';port='.substr($host,$pos+1).';dbname='.$dbname;
		}else{
			$dns = 'mysql:host='.$host.';dbname='.$dbname;
		}
		
		if(empty($options)){
			parent::__construct($dns,$username,$password);
			return true;
		}
		
		$sql = null;
		if(isset($options['charset'])){
			if(strtoupper($options['charset'])=='UTF-8'){
				$options['charset'] = 'utf8';
			}
			$sql = "SET NAMES '".$options['charset']."'";
			unset($options['charset']);
		}
		parent::__construct($dns,$username,$password,$options);
		if($sql){
			$this->exec($sql);
		}
	}
	
	public function fetchAll($sql, $bind = array(), $driver=array())
	{
		$result = $bind ? $this->_prepare($sql,$bind,$driver) : $this->query($sql);
		return $result ? $result->fetchAll(PDO::FETCH_ASSOC) : $result ;
	}

	public function fetchRow($sql,$arr=array(),$driver=array())
	{
		$result = $arr ? $this->_prepare($sql,$arr,$driver) : $this->query($sql);
		if($result){
			$aR = $result->fetch(PDO::FETCH_ASSOC);
			return $aR ? $aR : array();
		}
		return $result ;
	}

	public function fetchOne($sql,$arr=array(),$driver=array())
	{
		$aR = $this->fetchRow($sql,$arr,$driver);
		if($aR){
			return array_shift($aR);
		}else if($aR === array()){
			return null ;
		}
		return false ;
	}

	public function exec($sql,$arr=array(),$driver=array())
	{
		if(!$arr){
			$result = parent::exec($sql);
		}else{
			$result = $this->_prepare($sql,$arr,$driver);
			$result = $result->rowCount();
		}
		return $result;
	}


	public function getCount($sql)
	{
		$sql = 'SELECT COUNT(*) FROM ('.$sql.') AS T'.date('s');
		return $this->fetchOne($sql);
	}


	public function execBase()
	{
		$arguments = func_get_args();
		$temp = '';
		if(empty($arguments)){
			throw new Exception('Missing argument 1 for '.get_class($this).':execBase !');
		}
		$methodName = array_shift($arguments);
		if(empty($arguments)){
			return parent::$methodName();
		}
		$class_eval_str = ' $temp = parent::'.$methodName.'(';
		foreach($arguments as $key => $param){
			$class_eval_str.= '$arguments['.$key.'],';
		}
		$class_eval_str  = substr($class_eval_str,0,-1).');';
		eval($class_eval_str);
		return $temp;
	}

	public function update($table,$data,$where='')
	{
		if(!$data){
			return false;
		}
		$fields = array();
		$values = array();
		foreach($data as $k => $v){
			$fields[] = $k;
			$values[] = $this->quote($v);
		}
		$sql = 'UPDATE `'.$table.'` SET ' ;
		foreach($data as $k => $v){
			$sql.='`'.$k.'`='.$this->quote($v).',';
		}
		$sql = substr($sql,0,-1);
		if(trim($where)){
			$sql.= ' WHERE '.$where;
		}
		return $this->exec($sql);
	}

	
	public function replace($table,$aData)
	{
		$aKey = array();
		$aVal = array();
		foreach($aData as $k => $v){
			$aKey[] = '`'.$k.'`';
			$aVal[] = $this->quote($v);
		}
		$sql = 'REPLACE INTO `'.$table.'` ('.implode(',',$aKey).') VALUES ('.implode(',',$aVal).')';
		return $this->exec($sql);
	}
	
	
	public function fetchLimit($sql,$count=null,$offset=null)
	{
		return $this->fetchAll($this->_limit($sql,$count,$offset));
	}
	
	
	public function fetchPage($sql,$page=1,$count=null)
	{
		return $this->fetchAll($this->_limit($sql,$count,($page-1)*$count));
	}
	
	
	public function insert($table,$data,$isRtKey = true)
	{
		$arr = array();
		$arr[0] = $data;
		return $this->insertBatch($table,$arr,$isRtKey);	
	}
	
	public function insertBatch($table, $data = null, $isRtKey = false)
	{
		if(!$data){
			return false;
		}
		$fields = array();
		$values = array();
		if(isset($data[0])&&is_array($data[0])){//多维数组,批量插入
			$sql = 'INSERT INTO `'.$table.'` (`'.implode('`,`', array_keys($data[0])).'`) VALUES (';
			foreach($data as $k => $v){
				foreach($v as $kk => $vv){
					$sql .= $this->quote($vv).',' ;
				}
				$sql = substr($sql, 0, -1).'),(' ;
			}
			$sql = substr($sql, 0, -2);
			$t = $this->exec($sql);
			if($t){
				return $isRtKey ? $this->lastInsertId() : true;
			}
			return $t;
		}
		foreach($data as $k => $v){
			$fields[] = $k;
			$values[] = $this->quote($v);
		}
		$sql = 'INSERT INTO `'.$table.'` (`'.implode('`,`',$fields).'`) VALUES ('.implode(',',$values).')';
		$t = $this->exec($sql);
		if($t){
			return $isRtKey ? $this->lastInsertId() : true;
		}
		return $t;
	}

	
	public function delete($table,$where='')
	{
		$sql = 'DELETE FROM '.$table;
		if(trim($where)){
			$sql .= ' WHERE '.$where;
		}
		return $this->exec($sql);
	}
	
	
	public function getResource()
	{
		return parent;
	}
		
	private function _prepare($sql,$arr,$driver=array())
	{
		$sth = $this->prepare($sql,$driver);
		if(strpos($sql,'?')){
			$i=1;
			foreach($arr as $v){
				$sth->bindValue($i++,$v);
			}
		}else{
			foreach($arr as $k => $v){
				$sth->bindValue(':'.$k,$v);
			}
		}
		return $sth->execute() ? $sth : false;
	}
	
	private function _limit($sql,$count=null,$offset=null)
	{
		if(!$count && !$offset){
			return $sql;
		}
		if(!$offset){
			return $sql.' LIMIT '.$count ;
		}
		return $sql.' LIMIT '.$count.' OFFSET '.$offset ;
		//上面同： $sql.' LIMIT '.$offset.','.$count;
	}
}