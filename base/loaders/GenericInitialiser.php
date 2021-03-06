<?php
/* Copyright 2014, Zunautica Initiatives Ltd.
*  Distributed under the MIT License (http://opensource.org/licenses/MIT)
*/
namespace Teadaze;
/**
 * The abstract class for an object initialiser
 *
 * Object initialisers are the dependency injectors.
 * They take an object, check it's implementation and
 * set the dependencies. The methods used for setting 
 * the dependencies is based on the interface that
 * it implements.
 *
 * For instance - ModelLoadingInterface
 *
 * When the Initialiser comes across this interface
 * it will call it's method ModelLoadingInterface()
 *
 * So the injector methods have the same name as the
 * interface.
 *
 * This means that the instantiated Initialiser class
 * has to have methods for each type of dependency.
 */
abstract class GenericInitialiser {

	private $injectors = array();
	/**
	 * Used to passing in an object to initialise.
	 *
	 * This method absolutely has side effects - the object
	 * in question is initialised by reference.
	 *
	 * @method init($obj)
	 * @param &$obj Reference to the object being initialised
	 * @access public
	 */
	public function init($obj) {
		$impl = class_implements($obj);
		foreach($impl as $k => $v) {
			$m = str_replace('\\', '_', $k);
			if(method_exists($this, $m)) {
				$this->$m($obj);
			} else
			if(isset($this->injectors[$m])) {
				$this->injectors[$m]($obj);

			} else {
				$this->miss($obj, $k);
			}
		}
	}

	/**
	 * Generic miss method
	 *
	 * This will throw an exception if the method is not implemented
	 * in the class. This will probably never be called.
	 *
	 * @method miss($obj, $interface)
	 * @param $obj The object that is being initialised
	 * @param $interface The interface being loaded
	 * @access protected
	 */
	protected function miss($obj, $interface) {
		throw new Exception("Cannot initialise object with interface $interface");
	}

	/**
	 * Magic method for setting an interface injector on the fly
	 */
	public function __set($impl, $body) {
		if(method_exists($this, $impl) || isset($this->initMethods[$impl])) {
			throw new \Exception("Failed to set injector method for $impl -- already exists");
		}

		$this->injectors[$impl] = $body;
	}
}
