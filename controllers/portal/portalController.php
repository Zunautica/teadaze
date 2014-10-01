<?php

	class portalController extends Controller
	{
		public function init(array $url)
		{
			$this->loadView('main');
			$this->setVariable('title', "FOOBAR");
			return $this;
		}

		public function dynamic(array $url)
		{
			$this->loadView("ajax");
			$this->setVariable('values', 103476);
		}
	}
