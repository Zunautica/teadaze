<?php
	class LoadModel {
		private static $db = null;
		private static $models = array();

		public static function setDatabase($db) {
			if(self::$db)
				return;

			self::$db = $db;
		}

		public static function of($type, $model) {
			if(isset(self::$models[$model]))
				return self::$models[$model];
			$path = "models/$type/{$model}Model.php";
			if(!file_exists($path)) {
				throw new Exception("Model '$model' does not exist!<br />$path");
			}

			include($path);
			$class = "{$model}Model";
			$obj = new $class(self::$db);
			self::$models[$model] = $obj;

			return $obj;
		}
	}
