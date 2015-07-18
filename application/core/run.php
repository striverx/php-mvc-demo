<?php
	header("Content-Type: text/html; charset=utf-8");
	date_default_timezone_set('PRC');
	session_start();
	error_reporting(E_ALL);
	
	defined('BASE_PATH') or exit('非法操作！');
	/* 判断是否是ajax请求，用常量存储结果 */ 
	define('IS_AJAX', ((isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')) ? true : false);
	
	define('CORE_PATH',dirname(__FILE__).'/');
	
	define('__URL__','http://'.$_SERVER['SERVER_NAME'].$_SERVER['PHP_SELF']);
	
	define('SITE_PATH',dirname(__URL__).'/');

	/* 设置包含目录 */
	$include_path  = get_include_path();
	$include_path .= PATH_SEPARATOR.APP_PATH.'controller/';
	$include_path .= PATH_SEPARATOR.APP_PATH.'model/';
	$include_path .= PATH_SEPARATOR.APP_PATH.'view/';
	$include_path .= PATH_SEPARATOR.CORE_PATH.'class/';
	set_include_path($include_path);


	/* 加载基本函数文件 */
	require 'common/common.php';
	// 读取基本配置文件
	C(include 'config/config.php');
	// 读取自定义配置文件
	C(include C('APP_CONFIG'));
	// 加载常用函数文件
	require 'common/function.php';
	/* 加载自定义函数文件 */
	file_require(APP_PATH.'common/function.php');
	// 注册自动加载函数
	spl_autoload_register('autoload');	
	// 获取访问控制器
	$controller  = empty($_GET['c'])?C('DEFAULT_CONTROLLER'):ucfirst($_GET['c']);
	$controller .= 'Controller';
	if(!class_exists($controller)){
		$controller = C('DEFAULT_CONTROLLER').'Controller';
	}

	// 运行
	$m = new $controller;
	$m->run();



