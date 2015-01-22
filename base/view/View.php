<?php
/* Copyright 2014, Zunautica Initiatives Ltd.
 *  Distributed under the MIT License (http://opensource.org/licenses/MIT)
*/
namespace Teadaze;
/**
 * Abstract Class View for handling templates
 *
 * The View class is used to represent individual views, 
 * their title and any assets that they may be require for correct 
 * presentation once dispatched.
 *
 * This class also acts as the object generator
 * to create instances of itself using it's static method
 */
abstract class View extends AssetHandler
{
	/** @var TemplateContainer $container The template container */
	private $container;

	/** @var string $controller The name of the handling controller */
	private $controller;

	/** @var string $title The title of the page used in the browser */
	private $title = "";

	/** @var string $template Name of the template to load */
	private $template = "";


	/**
	* Set controller and generates a new template container
	*
	* When the object is instantiated it sets it's handling
	* controller, creates a new template container and finally
	* runs it's own init to handle configuration of the view
	*
	* @method __construct($controller)
	* @param string $controller The name of handling controller
	* @access public
	*/
	public function __construct($controller)
	{
		$this->controller = $controller;
		$this->container = new TemplateContainer();
		$this->init();
	}

	/**
	* This method is called externally to load and parse the template
	*
	* The template will be fully parsed and any variables passed
	* in will have been formatted by the template.
	* 
	* @method loadTemplate()
	* @access public
	* @return string A parsed template
	*/
	public function loadTemplate()
	{
		if($this->template == "")
			throw new Exception("Template not set in ".get_class($this));

		return $this->includeTemplate($this->template);
	}


	/**
	 * Used to set the template to load
	 *
	 * This is called usually in init to configure
	 * the template to load.
	 *
	 * @method setTemplate(string $name)
	 * @param string $name The name of the template to load
	 * @access protected
	 */
	protected function setTemplate($name)
	{
		$this->template = $name;
	}

	/**
	* Used to configure the view
	* 
	* This method is defined in child classes and is used
	* to configure assets, the template to load and the
	* title of the page. It is called when the object
	* is instantiated.
	*
	* @method init()
	* @access abstract public
	*/
	abstract public function init();


	/**
	* Set the title of the page for the browser
	*
	* This usually called in the init and is used
	* to set the name of the page in the <title>
	* element of the HTML
	*
	* @method setTitle(string $title)
	* @param string $title The title of the page
	* @access protected
	*/
	protected final function setTitle($title)
	{
		$this->title = $title;
	}

	/**
	 * Get the title set in the view
	 *
	 * @method getTitle()
	 * @access public
	 * @return string The title of the page
	 */
	public final function getTitle()
	{
		return $this->title;
	}

	/**
	 * Parse the template using the container
	 *
	 * @method includeTemplate(string $name)
	 * @param string $name The name of the template to include
	 * @access protected
	 * @return string A parsed and formatted template file
	 */
	protected final function includeTemplate($name)
	{
		return $this->container->loadTemplate("site/controllers/{$this->controller}/views/$name.php");
	}

	/**
	* Set a variable for the template in the container
	*
	* @method setVariable(string $var, mixed $value)
	* @param string $var The name of the template variable
	* @param mixed $value The value assigned to the variable
	* @access public
	*/
	public final function setVariable($var, $value)
	{
		$this->container->__set($var, $value);
	}

	/**
	 * Load up an instance of a view handled by a particular controller
	 *
	 * This is a static method that is used to load up a view. I feel it
	 * makes it easier to just say View::load since that is what you
	 * want to do. The method handles locating the correct file and
	 * keeps track of which views have already been loaded.
	 *
	 * @method load(string $controller, string $view)
	 * @param string $controller The name of the handling controller
	 * @param string $view The name of the view to load
	 * @access public
	 * @return View An object of a class inheritting View
	 */
	static public function load($controller, $view)
	{
		static $loaded = array();

		if(isset($loaded[$controller]))
			return $loaded[$controller];
		$path = "site/controllers/$controller/views/{$view}View.php";
		if(!file_exists($path)) {
			throw new Exception("Controller $controller's View '$view' does not exist!<br />$path");
		}

		include($path);
		$class = "{$view}View";
		$obj = new $class($controller);
		$loaded[$controller] = $obj;
		return $obj;
	}
}
