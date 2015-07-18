<?php
	
	class T {

		protected $tpl_vars;
		protected $left_delimiter = '<{';
		protected $right_delimiter = '}>';

		public function assign ($tpl_var, $value=null)
		{	
			// if (is_array($tpl_var)) {
			// 	foreach ($tpl_var as $_key => $_val) {
			// 		if ($_key != ''){
			// 			$this->tpl_vars[$_key] = $_val;
			// 		}
			// 	}
			// } else {
			// 	if ($tpl_var != '') {
			// 		$this->tpl_vars[$tpl_var] = $value;
			// 	}
			// }
			
			

		} 

		public function display($template)
		{	
			// ob_start();
			// include $template;
			// $content = ob_get_contents();
			// ob_end_clean();
			// foreach ($this->tpl_vars as $_key =>$_val ) {
			// 	$tpl_tags[]  = $this->left_delimiter.'$'.$_key.$this->right_delimiter;
			// 	$tpl_value[] = $_val->value; 
			// }
			// $str = str_replace($tpl_tags,$tpl_value,$content);
			// echo $str;
			
			V('fda');

			include $template;

		}

		public function V($key)
		{
			if ($key != '') {
				return empty($this->tpl_vars[$key])?'':$this->tpl_vars[$key];
			}

		}


	}

