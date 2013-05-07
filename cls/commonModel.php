<?php
class commonModel
{
	private static $dbs = array();
	
	public static function getDb($name)
	{		
		if(!isset(self::$dbs[$name])){
			global $config;
			$dbConfig = $config['db'][$name];
			
			self::$dbs[$name] = new mysqlPDO($dbConfig['host'],$dbConfig['username'],$dbConfig['password'],$dbConfig['dbname'],$dbConfig['options']);
		}
		return self::$dbs[$name];
	}
}