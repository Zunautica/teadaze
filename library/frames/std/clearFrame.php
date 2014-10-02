<?php
	class clearFrame extends Frame
	{
		public function init()
		{
			$this->setTemplate('clear');
			$this->addScript('global.js.php');
			$controller = Controller::load('locked');
			$controller->toggleRoot(false);
			$controller = $controller->init(array());
			$v = $controller->getView();
			$this->mergeAssets($v->getAssets());
			$this->setVariable('locked', $v);
		}
	}
