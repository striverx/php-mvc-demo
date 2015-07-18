<?php
	
	function halt($msg){
		$e = array();

		$trace = debug_backtrace();
		$e['file'] = $trace[0]['file'];
		$e['line'] = $trace[0]['line'];
		$e['msg']  = $msg;

		if(defined('DEBUG') && DEBUG==TRUE){
			include C('HALT_TPL');
		}else{
			include C('EXCEPTION_TPL');
		}
		exit;
	}
