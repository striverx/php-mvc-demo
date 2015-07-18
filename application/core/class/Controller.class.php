<?php
	
	class Controller {

		protected $controllerName = '';
		protected $actionName     = '';
		protected $tpl_vars;

		public function run()
		{
			// 初始化方法
			if (method_exists($this,'_initialize')) {
				$this->_initialize();
			}

			$action = empty($_GET['a'])?C('DEFAULT_ACTION'):$_GET['a'];

			if(!method_exists($this,$action))
				$action = C('DEFAULT_ACTION');

			// 设置当前操作的控制器名 和 方法名
			$this->controllerName = $this->getControllerName();
			$this->actionName     = $action;
			
			$this->$action();
		}

		public function assign($tpl_var, $value=null)
		{
			if (is_array($tpl_var)) {
				foreach ($tpl_var as $_key => $_val) {
					if ($_key != ''){
						$this->tpl_vars[$_key] = $_val;
					}
				}
			} else {
				if ($tpl_var != '') {
					$this->tpl_vars[$tpl_var] = $value;
				}
			}
		}

		// 加载模板文件
		public function display($template='')
		{

			$tplSuffix = C('TPL_SUFFIX');

			if (empty($temlate)) {
				$template = $this->controllerName.'/'.$this->actionName.$tplSuffix;
			} else {
				if (!strpos($template,'/'))
					$template = $this->controllerName.'/'.$template;
				
				if(strrchr($template,'.' != $tplSuffix))
					$template .= $tplSuffix;
			}
			//ob_start();
			include C('APP_VIEW').$template;
			// $str = ob_get_contents();
			// ob_end_clean();
			// echo $str;
		}

		public function V($tpl_var)
		{
			if ($tpl_var != '') {
				return empty($this->tpl_vars[$tpl_var])?'':$this->tpl_vars[$tpl_var];
			}

		}

		// 获取当前控制器名
		public function getControllerName(){
			if(empty($this->controllerName))
				return substr(get_class($this),0,-10);
			return $this->controllerName;
		}

		public function redirect($url=null,$param='',$time=0)
		{
			if ($url === null) {
				$c = $this->controllerName;
				$a = $this->actionName;
			} else {
				if (!strpos($url,'/')) {
					$c = $this->controllerName;
					$a = $url;
				} else {
					$c = substr($url,0,strpos($url,'/'));
					$a = substr($url,strpos($url,'/')+1);
				}
			}

			$url = __URL__.'?c='.$c.'&a='.$a.$param;

			if (!headers_sent()) {
				if ($time == 0) {
					header('Location: '.$url);
				} else {
					  header("refresh:{$time};url={$url}");
				} 
				
			} else {
				echo "<meta http-equiv='Refresh' content='{$time}';{$url}>";
			}
			exit();	
		}


	}