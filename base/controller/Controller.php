<?php
/* Copyright 2014, Zunautica Initiatives Ltd.
*  Distributed under the MIT License (http://opensource.org/licenses/MIT)
*/
	abstract class Controller extends ControlType
	{
		private $name = null;
		private $view = null;
		private $frame = null;
		private $root = true;

		private $access;

		public function __construct($name)
		{
			$this->name = $name;
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

		protected final function chainload($controller, $target)
		{
			$cnt = Controller::load($controller);
			return $cnt->init($target);
		}

		abstract public function init(array $target);

		public function dynamic(array $target)
		{
			return null;
		}

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
			$path = "site/controllers/$controller/{$controller}Controller.php";
			if(!file_exists($path)) {
				throw new Exception("Controller '$controller' does not exist!<br />$path");
			}

			include($path);
			$class = "{$controller}Controller";
			$obj = new $class($controller);
			$loaded[$controller] = $obj;
			return $obj;
		}

		protected final function loadModel($model)
		{
			$obj = parent::loadModel($model);
			global $hooks;
			$pattern = get_class($this).".".get_class($this->view).".$model";
			if(!isset($hooks[$pattern]))
				return $obj;

			return $this->loadWrapper($obj, $hooks[$pattern]);
		}

		private final function loadWrapper($model, $hooklines)
		{
			return new ModelWrapper($model, $hooklines);
		}
	}
