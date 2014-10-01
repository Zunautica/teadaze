<?php

	class lockedController extends Controller
	{
		public function init(array $url)
		{
			if($this->isRoot())
				return $this->chainload('errors', array('404'));

			$this->loadView('locked');
			return $this;
		}
	}
