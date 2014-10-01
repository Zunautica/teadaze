<?php
	abstract class Frame extends AssetHandler
	{
		private $container = null;
		private $template = null;
		private $frame = null;

		private $view = null;
		public function __construct($frame)
		{
			$this->container = new TemplateContainer();
			$this->frame = $frame;
		}

		public final function setView($view)
		{
			if(!$this->view) {
				$this->view = $view;
				$this->mergeAssets($this->view->getAssets());
				$this->setVariable('view', $view);
			}
		}

		abstract public function init();
		public final function show()
		{
			return $this->loadTemplate();
		}

		protected final function loadTemplate()
		{
			return $this->container->loadTemplate("frames/{$this->frame}/$this->template.php");
		}

		protected final function setVariable($var, $value)
		{
			$this->container->__set($var, $value);
		}

		protected final function setTemplate($template)
		{
			$this->template = $template;
		}

		public static function load($frame)
		{
			$frame = explode('.', $frame);
			if(!isset($frame[1]))
				throw new Exception("Malformed frame load");

			$path = "frames/{$frame[0]}/{$frame[1]}Frame.php";
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
