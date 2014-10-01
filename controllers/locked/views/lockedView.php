<?php
	class lockedView extends View
	{

		public function init()
		{
		}

		public function loadTemplate()
		{
			return $this->includeTemplate('locked');
		}
	}
