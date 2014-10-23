<?php
	abstract class ControlType extends PXBase
	{
		protected final function loadModel($model)
		{
			try {
			return Model::load($model);
			} catch(Exception $e) {
				echo $e->getMessage();
				die();
			}
		}

		protected final function redirect($url)
		{
			header("Location: $url");
		}
	}
