<?php
/* Copyright 2014, Zunautica Initiatives Ltd.
*  Distributed under the MIT License (http://opensource.org/licenses/MIT)
*/
	class TemplateContainer {
		private $properties = array();

		public function __set($name, $value) {
			$this->properties[$name] = $value;
		}

		public function __get($name) {
			if(!isset($this->properties[$name]))
				return null;

			return $this->properties[$name];
		}

		public function loadTemplate($t__path) {
			ob_start();
			include($t__path);
			return ob_get_clean();
		}
	}
