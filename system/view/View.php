<?php

	abstract class View
	{
		private $container;
		private $controller;

		private $scripts = array();
		private $styles = array();
		public function __construct($controller)
		{
			$this->controller = $controller;
			$this->container = new TemplateContainer();
		}

		abstract public function loadTemplate();
		protected function includeTemplate($name)
		{
			return $this->container->loadTemplate("controllers/{$this->controller}/views/$name.php");
		}

		public final function setVariable($var, $value)
		{
			$this->container->__set($var, $value);
		}

		static public function load($controller, $view)
		{
			static $loaded = array();

			if(isset($loaded[$controller]))
				return $loaded[$controller];
			$path = "controllers/$controller/views/{$view}View.php";
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
