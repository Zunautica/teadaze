<?php
namespace Teadaze;
/**
 * The abstract class for object loaders
 *
 * The child loader classes will implement
 * their own loading methods and automatically
 * pass the objects through the initialiser
 */
abstract class GenericLoader {
	protected $initialise = null;
	public final function setInitialiser(GenericInitialiser $initialiser) {
		$this->initialiser = $initialiser;
	}

	protected final function initialise(&$obj) {
		$this->initialiser->init($obj);
	}

	abstract public function load($type);
}
