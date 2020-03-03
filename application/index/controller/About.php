<?php

	namespace app\index\controller;
	use app\index\controller\Common;
	
	class About extends Common
	{
		public function index()
		{
			return $this->fetch('about');
		}
	}