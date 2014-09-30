<?php

	abstract class Controller {
		private $title;

		private $name;
		private $container;

		private $access;

		public function __construct($name)
		{
			$this->container = new TemplateContainer();
			$this->name = $name;
		}

		protected function loadTemplate($name)
		{
			return $this->container->loadTemplate("controllers/{$this->name}/views/$name.php");
		}

		protected function loadModel($model)
		{
			$model = explode('.', $model);
			try {
			return LoadModel::of($model[0], $model[1]);
			} catch(Exception $e) {
				echo $e->getMessage();
				die();
			}
		}

		protected final function setTitle($title)
		{
			$this->title = $title;
		}

		public final function getTitle()
		{
			return $this->title;
		}

		protected final function setVariable($var, $value)
		{
			$this->container->__set($var, $value);
		}

		abstract public function init(array $url);

		abstract public function show();
		static public function load($controller)
		{
			static $loaded = array();

			if(isset($loaded[$controller]))
				return $loaded[$controller];
			$path = "controllers/$controller/{$controller}Controller.php";
			if(!file_exists($path)) {
				throw new Exception("Controller '$controller' does not exist!<br />$path");
			}

			include($path);
			$class = "{$controller}Controller";
			$obj = new $class($controller);
			$loaded[$controller] = $obj;
			return $obj;
		}
	}
