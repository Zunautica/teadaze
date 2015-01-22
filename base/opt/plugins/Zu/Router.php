<?php
	/**
	 * A normal bundled basic router
	 *
	 * This is not a particularly complex router. If the route is in the
	 * page table then it will set the respective controller; otherwise it
	 * will leave the route as it were.
	 *
	 * The root table is setup in the following way:
	 *
	 * `$rtable['ROUTE_ALIAS'] = 'CONTROLLER'`
	 */
	class RouterPlugin extends \Teadaze\Plugin
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
