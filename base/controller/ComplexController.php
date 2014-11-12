<?php

/**
 * These are for when you want to import different controllers and 
 * merge their views into a single controller - thus creating composite
 * controllers
 */
abstract class ComplexController extends Controller {

	protected function importLoad($controller, $target) {
		$ctrl = Controller::load($controller);
		return $ctrl->runInit($target);
	}
}
