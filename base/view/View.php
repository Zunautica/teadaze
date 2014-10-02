<?php
/* Copyright 2014, Zunautica Initiatives Ltd.
*  Distributed under the MIT License (http://opensource.org/licenses/MIT)
*/
	abstract class View extends AssetHandler
	{
		private $container;
		private $controller;

		private $title = "";


		public function __construct($controller)
		{
			$this->controller = $controller;
			$this->container = new TemplateContainer();
			$this->init();
		}

		abstract public function loadTemplate();
		abstract public function init();


		protected final function setTitle($title)
		{
			$this->title = $title;
		}

		public final function getTitle()
		{
			return $this->title;
		}

		protected final function includeTemplate($name)
		{
			return $this->container->loadTemplate("site/controllers/{$this->controller}/views/$name.php");
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
