<?php
/* Copyright 2014, Zunautica Initiatives Ltd.
*  Distributed under the MIT License (http://opensource.org/licenses/MIT)
*/

/**
 * Another bundled router - this one is made to be wiki in style.
 *
 * It is wiki 'style' because it only allows the creation and editing 
 * of pages that already exist in the route table. This has be useful 
 * for quickly adding possible pages. It can be expanded by allowing
 * the addition of any page and if it doesn't exist, send it straight
 * to the editor.
 *
 * Usage:
 *
 * `domain.com/hello -> go to page 'hello'`
 *
 * `domain.com/hello/edit -> edit the page 'hello'`
 *
 * The handling controllers will receive the route name in their
 * target arrays, so they can ascertain the requested
 * page
 *
 * It takes several config options that can be set in the site entry.
 *
 * `$config['weditor'] = The editor controller, or complex controller`
 * `$config['wformatter'] = The controller that handles the formatting (viewing) of pages`
 * `$config['werror'] = Route alias to go if the page does not exist`
 *
 * The root table is setup in the following way:
 *
 * `$rtable['ROUTE_ALIAS'] = 'CONTROLLER'`
 *
 * The router only kicks in with static, full page requests. If it is a dynamic request
 * then the signal goes directly to the destination controller (Since the controller is
 * handling the specific request).
 */
	class WSRouterPlugin extends \Teadaze\Plugin
	{
		public function run(&$sinker)
		{
			global $config;
			if($sinker['target'][0] == $config['dynamic_keyword'])
				return;

			global $rtable;
			if(isset($sinker['target'][1]) && $sinker['target'][1] == 'edit') {
				$sinker['target'][1] = $sinker['target'][0];
				$sinker['target'][0] = $config['weditor'];
			} else {
				$sinker['target'][] = $sinker['target'][0];
			}

			if(!$rtable || !isset($sinker['target'][0])) {
				return;
			}

			if(isset($rtable[$sinker['target'][0]]))
				$sinker['target'][0] = $rtable[$sinker['target'][0]];
			else
				$sinker['target'] = array($config['wformatter'], $config['werror']);

		}
	}
