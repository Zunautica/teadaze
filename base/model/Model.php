<?php
/* Copyright 2014, Zunautica Initiatives Ltd.
*  Distributed under the MIT License (http://opensource.org/licenses/MIT)
*/
	include('TableModel.php');
	abstract class Model extends DBAccessor {
		protected function table($table)
		{
			return new PTableModel($this->db, $table);
		}

		public static function load($model) {
			static $models = array();
			$db = DBO::init();

			$atom = explode('.', $model);

			if(isset($models[$model]))
				return $models[$model];

			$path = "site/models/{$atom[0]}/{$atom[1]}Model.php";
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
