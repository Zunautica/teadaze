<?php
	class mainView extends View
	{

		public function init()
		{
			$this->addScript('portal.js');
			$this->setTitle("Portal");
		}

		public function loadTemplate()
		{
			return $this->includeTemplate('main');
		}
	}
