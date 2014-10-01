<?php
	class mainView extends View
	{

		public function init()
		{
			$this->addScript('lim.js');
			$this->setTitle("Portal");
		}

		public function loadTemplate()
		{
			return $this->includeTemplate('main');
		}
	}
