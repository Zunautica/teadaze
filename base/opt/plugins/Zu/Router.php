<?php

	class RouterPlugin extends \Teadaze\Plugin
	{
		public function run(&$sinker)
		{
			global $rtable;
			global $config;
			if(!$rtable || !isset($sinker['target'][0]))
				return;
			$sinker['target'][0] == $config['dynamic_keyword'] ?
				$this->dynamic($config['dynamic_keyword'], $sinker) :
				$this->normal($sinker);
		}

		private function dynamic($keyword, &$sinker) {
			global $rtable;
			if(isset($rtable["$keyword.{$sinker['target'][1]}"]))
				$sinker['target'][1] = $rtable["$keyword.{$sinker['target'][1]}"];
		}

		private function normal(&$sinker) {
			global $rtable;
			if(isset($rtable[$sinker['target'][0]])) {
				$sinker['target'][0] = $rtable[$sinker['target'][0]];
			}
		}
	}
