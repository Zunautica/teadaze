<?php
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
