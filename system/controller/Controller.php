<?php

	abstract class Controller {
		private $title;

		private $ctx;
		private $container;

		private $access;

		public function __construct($context)
		{
			$this->ctx = $context;
			$this->container = new TemplateContainer();
		}

		protected function loadTemplate($name)
		{
			return $this->container->loadTemplate("controller/{$this->ctx}/templates/$name.php");
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

		abstract public function init();
		abstract public function show();
	}
