<?php

	abstract class Controller {
		private $name = null;
		private $view = null;
		private $frame = null;
		private $root = true;

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

		protected final function setFrame($frame)
		{
			$this->frame = $frame;
		}

		public final function getFrame()
		{
			return $this->frame;
		}

		protected final function chainload($controller, $url)
		{
			$cnt = Controller::load($controller);
			return $cnt->init($url);
		}

		abstract public function init(array $url);

		public final function getView()
		{
			return $this->view;
		}

		public final function toggleRoot($flag)
		{
			$this->root = $flag;
		}

		protected final function isRoot()
		{
			return $this->root;
		}

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
