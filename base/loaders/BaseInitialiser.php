<?php
/* Copyright 2014, Zunautica Initiatives Ltd.
*  Distributed under the MIT License (http://opensource.org/licenses/MIT)
*/

/**
 * The Base interface based initialiser object for the framework
 * 
 * This class defines the interface initialisation for
 * the basic framework interfaces. The interfaces give
 * the initialiser the ability to inject dependencies
 *
 * The object does require basic object loaders to be set
 * before it is used. This should be handled automatically
 * by the framework entry object.
 */
class BaseInitialiser extends GenericInitialiser
implements ModelLoadingInterface, ControllerLoadingInterface, PluginLoadingInterface {
	private $modelLoader;
	private $controllerLoader;
	private $pluginLoader;
	private $db = null;

	public final function setControllerLoader(GenericLoader $obj) {
		$obj->setInitialiser($this);
		$this->controllerLoader = &$obj;
	}

	public final function setModelLoader(GenericLoader $obj) {
		$obj->setInitialiser($this);
		$this->modelLoader = &$obj;
	}

	public final function setPluginLoader(GenericLoader $obj) {
		$obj->setInitialiser($this);
		$this->pluginLoader = &$obj;
	}

	/**
	 * Dependency Injector for Database objects
	 *
	 * @method DBInterface(&$obj)
	 * @param &$obj reference to client object
	 * @access protected
	 */
	protected final function DBInterface($obj) {
		if(!$this->db)
			$this->db = DBO::init();

		$obj->setDatabase($this->db);
	}

	/**
	 * Dependency Injector for ModelLoading objects
	 *
	 * @method ModelLoadingInterface(&$obj)
	 * @param &$obj reference to client object
	 * @access protected
	 */
	protected final function ModelLoadingInterface($obj) {
		$obj->setModelLoader($this->modelLoader);
	}

	/**
	 * Dependency Injector for ControllerLoading objects
	 *
	 * @method ControllerLoadingInterface(&$obj)
	 * @param &$obj reference to client object
	 * @access protected
	 */
	protected final function ControllerLoadingInterface($obj) {
		$obj->setControllerLoader($this->controllerLoader);
	}

	/**
	 * Dependency Injector for PluginLoading objects
	 *
	 * @method PluginLoadingInterface(&$obj)
	 * @param &$obj reference to client object
	 * @access protected
	 */
	protected final function PluginLoadingInterface($obj) {
		$obj->setPluginLoader($this->pluginLoader);
	}
}
