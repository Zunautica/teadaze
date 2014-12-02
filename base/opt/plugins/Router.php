<?php

	class RouterPlugin extends Plugin
	{
		public function run(&$sinker)
		{
			global $rtable;
			if(!$rtable || !isset($sinker['target'][0]))
				return;

			if(isset($rtable[$sinker['target'][0]]))
				$sinker['target'][0] = $rtable[$sinker['target'][0]];
		}
	}
