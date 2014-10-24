<?php
	class ModelWrapper
	{
		private $model;
		private $hooklines;
		public function __construct($model, $hooklines)
		{
			$this->model = $model;
			$this->hooklines = $hooklines;
		}

		public function __call($method, $arguments)
		{
			if(!isset($this->hooklines[$method]))
				return call_user_func_array(array($this->model, $method), $arguments);

			return $this->__hook($method, $arguments);
		}

		private function __hook($hook, $arguments)
		{
			$this->__hookline($this->hooklines[$hook]);
			$data = call_user_func_array(array($this->model, $hook), $arguments);
			foreach($this->hooklines[$hook] as $slot) {
				$plugin = Plugin::load($slot[0]);
				$plugin->$slot[1]($data);
			}
			return $data;
		}

		private function __hookline(&$line)
		{
			$line = explode(';', $line);
			foreach($line as &$l)
				$l = explode('::', $l);
		}
	}
