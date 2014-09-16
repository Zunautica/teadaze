<?php

	class TemplateContainer {
		private $properties = array();

		public function __set($name, $value) {
			$this->properties[$name] = $value;
		}

		public function __get($name) {
			return $this->properties[$name];
		}

		public function loadTemplate($t__path) {
			ob_start();
			include($t__path);
			return ob_get_clean();
		}
	}
