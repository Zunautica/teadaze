<?php
/* Copyright 2014, Zunautica Initiatives Ltd.
*  Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */
namespace Teadaze;

/**
 * Container for templates, for handling loading by the view
 *
 * These are created automatically by the view. When you're
 * working on a template, $this will be pointing to the instance
 * of this class. Any variables passed in will be found here
 */
class TemplateContainer {

	/** @var array $properties this is a list of variables passed into the template */
	private $properties = array();

	/**
	 * Magic method for adding a new template variable
	 *
	 * @method __set(string $name, mixed $value)
	 * @param string $name The name of the variable
	 * @param mixed $value The value assigned to the variable
	 * @access public
	 */
	public function __set($name, $value) {
		$this->properties[$name] = $value;
	}

	/**
	 * Magic method for getting a template variable
	 *
	 * @method __get(string $name)
	 * @param string $name The name of the variable to retrieve
	 * @access public
	 * @return mixed The value of the variable
	 */
	public function __get($name) {
		if(!isset($this->properties[$name]))
			return null;

		return $this->properties[$name];
	}

	/**
	 * This is called when the template is to be parsed
	 * 
	 * It uses a variable that is probably not going to be used
	 * by the template for the path to load. The template is included
	 * and the output is pulled from an output buffer
	 *
	 * @method loadTemplate(string $t__path)
	 * @param string $t__path The path of the template
	 * @access public
	 * @return string A parsed template
	 */
	public function loadTemplate($t__path) {
		if(!file_exists($t__path))
			throw new \Exception("Template $t__path does not exist");
		ob_start();
		include($t__path);
		return ob_get_clean();
	}
}
