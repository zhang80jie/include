<?php
abstract class parentController
{
	public function __construct()
	{
		if(method_exists($this, 'init')){
			$this->init();
		}
		$this->render();
	}
	
	public function render()
	{
		//默认调用index
		$methodName = 'indexAction';
		
		//接收act参数并映射到对应的action
		if(isset($_REQUEST['act']) && !empty($_REQUEST['act'])){
			$methodName = $_REQUEST['act'].'Action';	
		}

		//执行controller 的 action
		if(method_exists($this, $methodName)){
			$this->$methodName();
		}
	}
}