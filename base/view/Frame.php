<?php
/* Copyright 2014, Zunautica Initiatives Ltd.
*  Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */
namespace Teadaze;
/**
 * A temporary fix for handling the surrounding frame of a view.
 *
 * This is used until composite controllers are implemented.
 * A controller can specify a frame which will be used to surround
 * whatever the view has generate.
 * 
 * A frame acts like a sort of composite view. However, it is bad design]
 * and is merely a place holder until it's functionality is deprecated
 */
	abstract class Frame extends AssetHandler
	{
		/** @var TemplateContainer $container A template container */
		private $container = null;

		/** @var string $template The name of template to load */
		private $template = null;

		/** @var string $frame the name of the frame */
		private $frame = null;

		/** @var View $view the view object that has been passed in for rendering */
		private $view = null;

		/**
		 * The frame is always instantiated with a TemplateContainer and name
		 * 
		 * @method __construct($frame)
		 * @param string $frame The name of the frame
		 * @access public
		 */
		public function __construct($frame)
		{
			$this->container = new TemplateContainer();
			$this->frame = $frame;
		}

		/**
		 * Sets the view that will be rendered by the frame
		 *
		 * Pass in the instantiated View object. The frame can
		 * only have one view assigned to it.
		 *
		 * @method setView(View $view)
		 * @param View $view The instantiated view object to use
		 * @access public
		 */
		public final function setView($view)
		{
			if(!$this->view) {
				$this->view = $view;
				$this->mergeAssets($this->view->getAssets());
				$this->setVariable('view', $view);
			}
		}

		/**
		* The init method that is defined by child classes
		*
		* This method is called to initialise the child object.
		* This method is defined by the child class and is used
		* to setup assets, templates etc. for a particular frame.
		*
		* @method init()
		* @access public
		*/
		abstract public function init();

		/**
		 * A method to load and parse the frame's template
		 *
		 * This is called externally when the frame is to finally parse
		 * it's template with any variables that have been passed in.
		 *
		 * There is a standard template variable that is passed in called 
		 * 'assets' and this will be the final assets array to be used.
		 *
		 * @method show()
		 * @access public
		 * @return string A fully parsed template
		 */
		public final function show()
		{
			$this->setVariable('assets', $this->getAssets());
			return $this->loadTemplate();
		}

		/**
		 * The method for loading the template through the TemplateContainer
		 *
		 * This is called internally by show() to load the final template
		 *
		 * @method loadTemplate()
		 * @access private
		 * @return string A fully parsed template
		 */
		private final function loadTemplate()
		{
			return $this->container->loadTemplate("site/frames/{$this->frame}/$this->template.php");
		}

		/**
		 * Set a template variable
		 *
		 * Use this method to set a template variable in the TemplateContainer
		 * 
		 * @method setVariable(string $var, mixed $value)
		 * @param string $var The name of the template variable
		 * @param mixed $value The value to assign to the variable
		 * @access protected
		 */
		protected final function setVariable($var, $value)
		{
			$this->container->__set($var, $value);
		}

		/**
		 * Set the name of the template file to finally load
		 *
		 * This will be the name of the template to load when the
		 * object show() called.
		 *
		 * @method setTemplate(string $template)
		 * @param string $template The name of the template to load
		 * @access protected
		 */
		protected final function setTemplate($template)
		{
			$this->template = $template;
		}

		/**
		 * The static method for loading an instance of a frame
		 *
		 * This method is used to load an instance of a partiuclar frame.
		 * The frame is specified in a 'package.frame' format.
		 * 
		 * The method handles the location and instantation of the the frame
		 *
		 * @method load(string $frame)
		 * @param string $frame The name of the frame to load in 'package.frame' format
		 * @access public
		 * @return Frame A fully instantiated and initialised Frame object
		 */
		public static function load($frame)
		{
			$frame = explode('.', $frame);
			if(!isset($frame[1]))
				throw new Exception("Malformed frame load");

			$path = "site/frames/{$frame[0]}/{$frame[1]}Frame.php";
			if(!file_exists($path)) {
				throw new Exception("Frame '$frame.$template' does not exist!<br />$path");
			}

			include($path);
			$class = "{$frame[1]}Frame";
			$obj = new $class($frame[0]);
			$obj->init();
			return $obj;
		}
	}
