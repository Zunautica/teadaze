<?php

	abstract class Model extends DBAccessor {
		public static function load($model) {
			static $models = array();
			$db = DBO::init();

			$atom = explode('.', $model);

			if(isset($models[$model]))
				return $models[$model];

			$path = "models/{$atom[0]}/{$atom[1]}Model.php";
			if(!file_exists($path)) {
				throw new Exception("Model '{$model}' does not exist!<br />$path");
			}

			include($path);
			$class = "{$atom[1]}Model";
			$obj = new $class($db);
			$models[$model] = $obj;

			return $obj;
		}
	}
