<?php
/* Copyright 2014, Zunautica Initiatives Ltd.
*  Distributed under the MIT License (http://opensource.org/licenses/MIT)
*/

/**
 * The abstract class for control flavoured classes
 *
 * Control flavoured classes are Controllers and Plugins.
 * They share some similar behaviour which is defined here.
 */
	abstract class ControlType extends PXBase
	{
		/**
		 * Used to load a Model object using 'package.model'
		 *
		 * The model is specified using it's subdir (package)
		 * and the name of the model. For instance you could 
		 * request 'Users.OpenID' to load the openID model
		 * from the Users package
		 *
		 * @method loadModel(string $model)
		 * @param string $model The model name in 'package.model' format
		 * @access protected
		 * @return Model A fully instantiated Model
		 */
		protected function loadModel($model)
		{
			try {
			return Model::load($model);
			} catch(Exception $e) {
				echo $e->getMessage();
				die();
			}
		}

		/**
		 * Send out a relocation header
		 *
		 * Since control flavoured object sometimes
		 * need to redirect the browser, this method
		 * provides this functionality. Enter the URL
		 * for the redirect.
		 *
		 * @method redirect(string $url)
		 * @param string $url The URL to redirect the browser to
		 * @access protected
		 */
		protected final function redirect($url)
		{
			header("Location: $url");
		}
	}
