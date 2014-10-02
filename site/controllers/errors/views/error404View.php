<?php
	class error404View extends View
	{

		public function init()
		{
		}

		public function loadTemplate()
		{
			return $this->includeTemplate('404');
		}
	}
