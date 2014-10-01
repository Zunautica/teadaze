<?php
	class ajaxView extends View
	{

		public function init()
		{
		}

		public function loadTemplate()
		{
			return $this->includeTemplate('ajax');
		}
	}
