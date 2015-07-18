<?php
	
	function autoload($classname)
	{
		include ucfirst($classname).'.class.php';
	}

	/**
	 * [C 读取或设置配置 用于操作配置文件]
	 * @param [type] $name  [description]
	 * @param string $value [description]
	 */
	function C($name,$value='')
	{
		static $config = array();

		if (empty($name)) return null;

		if (is_string($name)) {
			$name = strtolower($name);
			if (empty($value)) {
				return empty($config[$name])?null:$config[$name];
			} else {
				$config[$name] = $value;
			}
		}

		if (is_array($name)) {
			$config = array_merge($config, array_change_key_case($name));
		}
	}


	function file_require($file)
	{
		if (file_exists($file)) {
			require $file;
		}
	}

	function dump($array)
	{
		echo '<pre>';
		var_dump($array);
		echo '</pre>';
	}

