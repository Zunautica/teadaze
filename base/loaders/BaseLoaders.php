<?php
/* Copyright 2014, Zunautica Initiatives Ltd.
*  Distributed under the MIT License (http://opensource.org/licenses/MIT)
*/

/**
 * @file
 * This is a collections of the basic object loaders
 */

/**
 * The loader object for controllers
 *
 * The loader will automatically send the newly created
 * object through the initialiser.
 */
class ControllerLoader extends GenericLoader {
	/**
	* Method for loading instances of Controllers
	*
	* This is the method that can be called to instantiate
	* a particular controller. It handles all the loading and
	* configuring of the controller and passes back the 
	* instantiated object.
	*
	* @method load(string $controller)
	* @param string $controller The name of the controller to load
	* @access public
	* @return Controller An instance of Controller
	*/
	public function load($controller) {
		static $loaded = array();
		if(isset($loaded[$controller]))
			return $loaded[$controller];
		$path = "site/controllers/$controller/{$controller}Controller.php";
		if(!file_exists($path)) {
			throw new Exception("Controller '$controller' does not exist!<br />$path");
		}

		include($path);
		$class = "{$controller}Controller";
		$obj = new $class($controller);
		$this->initialise($obj);
		$loaded[$controller] = $obj;
		return $obj;
	}
}

/**
 * The loader object for models
 *
 * The loader will automatically send the newly created
 * object through the initialiser.
 */

class ModelLoader extends GenericLoader {
	/**
	 * The method for loading a model
	 *
	 * This is used to load a model object. It handles
	 * locating the file, including and instantiating
	 * the model object. It also tracks which models have
	 * been loaded already
	 *
	 * The model is loaded in 'package.model' format, so
	 * for example:
	 *
	 * 'Std.Users'
	 *
	 * will load the 'Users' model in the subdirectory 'Std'
	 *
	 * @method load(string $model)
	 * @param string $model The name of the model in 'package.model' format
	 * @access public
	 * @return Model The instantiated model object
	 */
	public function load($model) {
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
		$this->initialise($obj);
		return $obj;
	}
}

/**
 * The loader object for plugins
 *
 * The loader will automatically send the newly created
 * object through the initialiser.
 */
class PluginLoader extends GenericLoader {
	/**
	 * The method for loading a plugin
	 *
	 * This method will automatically locate the plugin
	 * and instantiate it. If it is already loaded then it
	 * will pass back the instantiated object.
	 *
	 * The method expects the specified plugin to be in
	 * 'package.plugin' format.
	 *
	 * @method load(string $plugin)
	 * @param string $plugin The plugin to load in 'package.plugin' format
	 * @access public
	 * @return Plugin An instantiated plugin object
	 */
	public function load($plugin) {
		static $plugins = array();
		$db = DBO::init();

		$atom = explode('.', $plugin);

		if(isset($plugins[$plugin]))
			return new $plugins[$plugin]();

		$path = "site/plugins/{$atom[0]}/{$atom[1]}.php";
		if(!file_exists($path)) {
			throw new Exception("Plugin '{$plugin}' does not exist!<br />$path");
		}

		include($path);
		$class = "{$atom[1]}Plugin";
		$obj = new $class($db);
		$plugins[$plugin] = $class;
		$this->initialise($obj);
		return $obj;
	}
}
