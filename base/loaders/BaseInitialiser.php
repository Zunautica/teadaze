<?php
/* Copyright 2014, Zunautica Initiatives Ltd.
*  Distributed under the MIT License (http://opensource.org/licenses/MIT)
*/
namespace Teadaze;
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
implements ModelLoadingInterface, ControllerLoadingInterface, PluginLoadingInterface, ViewLoadingInterface {
	protected $modelLoader;
	protected $controllerLoader;
	protected $pluginLoader;
	protected $viewLoader;
	protected $db = null;

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

	public final function setViewLoader(GenericLoader $obj) {
		$obj->setInitialiser($this);
		$this->viewLoader = &$obj;
	}

	/**
	 * Dependency Injector for Database objects
	 *
	 * @method DBInterface(&$obj)
	 * @param &$obj reference to client object
	 * @access protected
	 */
	protected final function Teadaze_DBInterface($obj) {
		
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
	protected final function Teadaze_ModelLoadingInterface($obj) {
		$obj->setModelLoader($this->modelLoader);
	}

	/**
	 * Dependency Injector for ControllerLoading objects
	 *
	 * @method ControllerLoadingInterface(&$obj)
	 * @param &$obj reference to client object
	 * @access protected
	 */
	protected final function Teadaze_ControllerLoadingInterface($obj) {
		$obj->setControllerLoader($this->controllerLoader);
	}

	/**
	 * Dependency Injector for PluginLoading objects
	 *
	 * @method PluginLoadingInterface(&$obj)
	 * @param &$obj reference to client object
	 * @access protected
	 */
	protected final function Teadaze_PluginLoadingInterface($obj) {
		$obj->setPluginLoader($this->pluginLoader);
	}

	/**
	 * Dependency Injector for ViewLoading objects
	 *
	 * @method ViewLoadingInterface(&$obj)
	 * @param &$obj reference to client object
	 * @access protected
	 */
	protected final function Teadaze_ViewLoadingInterface($obj) {
		$obj->setViewLoader($this->viewLoader);
	}

	/**
	 * Attempt an unimplemented initialisation for an object
	 *
	 * This will attempt to init an object based on the interface
	 * and its namespace and the type hint
	 *
	 * @method miss($obj, $interface)
	 * @param $obj The object that is being initialised
	 * @param $interface The interface being loaded
	 * @access protected
	 */
	protected function miss($obj, $interface) {
		$atoms = explode('\\', $interface);
		$method = "set".end($atoms);
		$v = new \ReflectionParameter(array(get_class($obj), $method), 0);
		$c = $v->__toString();
		$ref = explode(" ",$c)[4];
		if(preg_match('/Model$/', $ref, $d) == 1) {
			if(isset($atoms[1])) {
				$atoms = array_slice($atoms, 0, -1);
				$ref = implode('.',$atoms).".".substr($ref, 0,-5);
			}
			$m = $this->modelLoader->load($ref);
			$obj->$method($m);
		} else 
		if(preg_match('/Plugin$/', $ref) == 1) {
			if(isset($atoms[1]))
				$ref = "{$atoms[0]}.".substr($ref, 0,-5);
			$this->controllerLoader->load($ref);
		} else {
			throw new \Exception("Cannot initialise object with interface $interface");
		}
		
	}


}
