<?php
/**
 * Model 基类
 * @author jiangjituo
 */
abstract class parentModel
{	
	protected $db;
	
	protected function __construct()
	{
		$this->getDb();
		
		if(method_exists($this,'init')){
			$this->init();
		}
	}
		

	
	/**
	 * 获取数据库连接
	 */
	protected function getDb($name='default')
	{
		$this->db = commonModel::getDb($name);
	}
	
}
