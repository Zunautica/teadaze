<?php
include('base/init.php');
class Entry
{
	private $db;
	private function init()
	{
		$this->db = DBO::init();
	}

	public function includeConfig($config)
	{
		if(is_array($config)) {
			foreach($config as $c)
				include("site/config/$c.php");
		} else
			include("site/config/$config.php");
	}

	public function run()
	{
		global $config;

		$this->init();
		$url = url_array($_SERVER['REQUEST_URI']);
		if($url == null)
			$url[0] = $config['auto_control'];

		try {
			if($url[0] == $config['dynamic_keyword'])
				return $this->runDynamic(url_next_dir($url));

			return $this->runStatic($url);
		} catch (exception $e) {
			throw new Exception("<strong>Error on run()</strong><br />$e");
			return null;
		}

	}

	private function runStatic($url)
	{
		global $config;
		try {
			$controller = Controller::load($url[0]);
			$controller = $controller->init(url_next_dir($url));

			$frame = $controller->getFrame();
			if($frame == null)
				$frame = $config['auto_frame'];

			$view = $controller->getView();
			$frame = Frame::load($frame);
			$frame->setView($view);
			return $frame->show();
		}
		catch(exception $e) {
			throw $e;
		}
	}

	private function runDynamic($url)
	{
		global $config;
		try {
			$controller = Controller::load($url[0]);
			$controller->dynamic(url_next_dir($url));
			$view = $controller->getView();
			if(!$view)
				return null;
			return $view->loadTemplate();
		}
		catch(exception $e) {
			throw $e;
		}
	}
}
