<?php

	class PortalController extends Controller {

		public function init()
		{
			$this->setTitle('Portal');
			$this->setVariable('title', "FOOBAR");
		}

		public function show()
		{
			return $this->loadTemplate('main');
		}
	}
