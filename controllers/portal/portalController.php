<?php

	class portalController extends Controller {

		public function init(array $url)
		{
			$this->setTitle('Portal');
			$this->setVariable('title', "FOOBAR");
		}

		public function show()
		{
			return $this->loadTemplate('main');
		}
	}
