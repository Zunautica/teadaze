<?php

	abstract class Controller {
		private $title = "";

		private $name = null;
		private $view = null;

		private $access;

		public function __construct($name)
		{
			$this->name = $name;
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

		protected function loadView($view)
		{
			try { 
				$this->view = View::load($this->name, $view);
			} catch (exception $e) {
				echo $e;
			}
		}

		protected final function loadTemplate()
		{
			if($this->view == null)
				return null;

			return $this->view->loadTemplate();
		}


		protected function setVariable($var, $value)
		{
			if($this->view == null)
				return;

			$this->view->setVariable($var, $value);
		}

		protected final function setTitle($title)
		{
			$this->title = $title;
		}

		public final function getTitle()
		{
			return $this->title;
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
