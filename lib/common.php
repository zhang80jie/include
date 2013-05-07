<?php
/**
 * 公共函数库
 */

/**
 * 自动加载函数
 * @param string $className
 */
function loadClass($className)
{
	$filePath = ROOT_PATH.'include/cls/'.$className.'.php';
	if(file_exists($filePath)){
		include $filePath;
	}
}

//注册自动加载函数
spl_autoload_register('loadClass');