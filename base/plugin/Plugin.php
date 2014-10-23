<?php
/* Copyright 2014, Zunautica Initiatives Ltd.
*  Distributed under the MIT License (http://opensource.org/licenses/MIT)
*/
	abstract class Plugin extends ControlType
	{
		abstract public function run(&$sinker);


		static public function load($plugin)
		{
			static $plugins = array();
			$db = DBO::init();

			$atom = explode('.', $plugin);

			if(isset($plugins[$plugin]))
				return $models[$plugin];

			$path = "site/plugins/{$atom[0]}/{$atom[1]}.php";
			if(!file_exists($path)) {
				throw new Exception("Plugin '{$plugin}' does not exist!<br />$path");
			}

			include($path);
			$class = "{$atom[1]}Plugin";
			$obj = new $class($db);
			$plugins[$plugin] = $obj;

			return $obj;

		}
	}
