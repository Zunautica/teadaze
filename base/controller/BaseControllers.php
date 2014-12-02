<?php
/* Copyright 2014, Zunautica Initiatives Ltd.
*  Distributed under the MIT License (http://opensource.org/licenses/MIT)
*/

/**
 * A controller that loads up other controllers
 *
 * These are for when you want to import different controllers and 
 * merge their views into a single controller - thus creating composite
 * views.
 *
 * When you want to have say, a surrounding template, you can chainload
 * the template and the merge other control views into the template.
 *
 * This means the ComplexController will be transparent and the final
 * controller that will be handled is the chainloaded controller that
 * has had other views merged into it.
 */
abstract class ComplexController extends Controller {

	/**
	 * Load a controller without chainloading it
	 *
	 * This will return an initialised controller object without
	 * setting it as the reference to pass back.
	 *
	 * This allows the ComplexController to load many different
	 * controllers in order to merge their views into other 
	 * controllers
	 *
	 * @param string $controller The name of the controller to import
	 * @param array $target The target array to pass to the controller
	 * @access protected
	 * @return Controller An initialised controller object
	 */
	protected final function importLoad($controller, $target) {
		$ctrl = $this->controllerLoader->load($controller);
		return $ctrl->runInit($target);
	}
}

/**
 * A controller that performs a single task
 *
 * They can display the contents of a news page, or act as
 * the text editor or as a template frame etc. 
 *
 * These are loaded up by complex controllers or can be 
 * loaded up individually
 */
abstract class TaskController extends Controller {
}
