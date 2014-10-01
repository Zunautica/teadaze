<?php

	class errorsController extends Controller {

		public function init(array $url)
		{
			if($url[0] == '404')
				$this->loadView('error404');

			return $this;
		}

		public function show()
		{
			return $this->loadTemplate();
		}
	}
