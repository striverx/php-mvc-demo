<?php
	
	class IndexController extends CommonController {

		public function index(){

			$abc = 'fdafafasfa';
			$this->assign('abc',$abc);
			$this->assign('abc2',array(1,2,3,4,5));
			$this->display();
		}

		public function test(){
		}
		

	}