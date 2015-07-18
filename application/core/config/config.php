<?php
	return array(
		'APP_CONFIG' 				=> APP_PATH.'config/config.php',        // 应用配置文件
		'APP_VIEW' 					=> APP_PATH.'view/',				    // 应用模板文件夹
		'TPL_SUFFIX'       			=> '.tpl',                              // 应用模板文件后缀
		'DEFAULT_CONTROLLER'        => 'Index',                             // 默认控制器
		'DEFAULT_ACTION'            => 'index',                             // 默认方法
		'HALT_TPL'                  => CORE_PATH.'tpl/halt.tpl',            // 错误显示模板
		'EXCEPTION_TPL'             => CORE_PATH.'tpl/exception.tpl',       // 错误显示模板
		'LOG_FILE'                  => CORE_PATH.'log/log.txt',             // 错误日志文件
		'__CSS__'                   => SITE_PATH.APP_NAME.'/statics/css/',            
		'__IMG__'                   => SITE_PATH.APP_NAME.'/statics/img/',             
		'__JS__'                    => SITE_PATH.APP_NAME.'/statics/js/',             
	);