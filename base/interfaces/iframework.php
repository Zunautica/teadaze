<?php
/* Copyright 2014, Zunautica Initiatives Ltd.
*  Distributed under the MIT License (http://opensource.org/licenses/MIT)
*/

/**
 * Dependency Interface for objects requiring database access
 */
interface DBInterface {
	/**
	 * Set the DBO object in the object
	 *
	 * @method setDatabase($db)
	 * @param $db The databas object to pass in
	 * @access public
	 */
	public function setDatabase($db);
}

/**
 * Dependency Interface for objects that will be loading Model objects
 */
interface ModelLoadingInterface {
	/**
	 * Set the ModelLoader in the object
	 *
	 * @method setModelLoader(GenericLoader &$modelLoader)
	 * @param GenericLoader $modelLoader The model loader object to pass in
	 * @access public
	 */
	public function setModelLoader(GenericLoader $modelLoader);
}

/**
 * Dependency Interface for objects that will be loading Controller objects
 */
interface ControllerLoadingInterface {
	/**
	 * Set the ControllerLoader in the object
	 *
	 * @method setControllerLoader(GenericLoader &$controllerLoader)
	 * @param GenericLoader $controllerLoader The controller loader object to pass in
	 * @access public
	 */
	public function setControllerLoader(GenericLoader $controllerLoader);
}

/**
 * Dependency Interface for objects that will be loading Plugin objects
 */
interface PluginLoadingInterface {
	/**
	 * Set the PluginLoader in the object
	 *
	 * @method setPluginLoader(GenericLoader &$pluginLoader)
	 * @param GenericLoader $pluginLoader The plugin loader object to pass in
	 * @access public
	 */
	public function setPluginLoader(GenericLoader $pluginLoader);
}
