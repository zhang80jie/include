<?php


ini_set("session.use_trans_sid",1); //自动传递sessionid 
ini_set("session.name","sid");   //设置传递session的名字 
ini_set("url_rewriter.tags","a=href,go=href,area=href,frame=src,input=src,form=,fieldset=");  //标签重定义 
session_start();

//网站根目录
define('ROOT_PATH',str_replace('include/lib/init.php', '', str_replace('\\', '/', __FILE__)));


//统一设置页面编码方式
header("Content-type: text/html; charset=utf-8");


//引入配置文件
$config = require ROOT_PATH.'include/conf/config.php';

//引入公共函数库
require ROOT_PATH.'include/lib/common.php';

//引入腾讯 sdk
require ROOT_PATH.'include/sdk/OpenApiV3.php';



