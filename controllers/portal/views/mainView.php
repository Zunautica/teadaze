<?php
	class mainView extends View
	{

		public function init()
		{
			$this->addScript('lim.js');
		}

		public function loadTemplate()
		{
			return $this->includeTemplate('main');
		}
	}
