<?php
	class clearFrame extends Frame
	{
		public function init()
		{
			$this->setTemplate('clear');
			$controller = Controller::load('locked');
			$controller->toggleRoot(false);
			$controller = $controller->init(array());
			$v = $controller->getView();
			$this->mergeAssets($v->getAssets());
			$this->setVariable('locked', $v);
		}
	}
