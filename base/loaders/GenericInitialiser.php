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
			$k = str_replace('\\', '_', $k);
			$this->$k($obj);
		}
	}
}
