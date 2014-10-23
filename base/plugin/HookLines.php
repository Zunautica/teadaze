<?php
/* Copyright 2014, Zunautica Initiatives Ltd.
*  Distributed under the MIT License (http://opensource.org/licenses/MIT)
*/

	class HookLines
	{
		private $lines = array();
		public function __construct($hooks)
		{
			foreach($hooks as $hook => $line)
				$this->lines[$hook] = $line;
		}

		public function addPlugin($plugin)
		{
			$this->lines[$plugin->getHook()][] = $plugin;
		}

		public function run($hook, &$sinker)
		{
			if(!isset($this->lines[$hook]))
				return;

			foreach($this->lines[$hook] as $name) {
				$plugin = Plugin::load($name);

				$plugin->run($sinker);
			}
		}
	}
