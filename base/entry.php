<?php
/* Copyright 2014, Zunautica Initiatives Ltd.
*  Distributed under the MIT License (http://opensource.org/licenses/MIT)
*/

include('base/init.php');
class Entry
{
	private $db;
	private $hooks;

	private function init()
	{
		global $hooks;
		$this->hooks = new HookLines($hooks);
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
			$this->runHook('onrequest', $url);
			if($url[0] == $config['dynamic_keyword'])
				return $this->runDynamic(url_next_dir($url));

			return $this->runStatic($url);
		} catch (exception $e) {
			throw new Exception("<strong>Error on run()</strong><br />$e");
			return null;
		}

	}

	private function runStatic($target)
	{
		global $config;
		try {
			while(!$this->runHook($target[0]."Controller", $target))
				continue;

			$controller = Controller::load($target[0]);
			$controller = $controller->init(url_next_dir($target));

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

	private function runDynamic($target)
	{
		global $config;
		try {
			$config['debug'] = false;
			$controller = Controller::load($target[0]);
			$controller->dynamic(url_next_dir($target));
			$view = $controller->getView();
			if(!$view)
				return null;
			return $view->loadTemplate();
		}
		catch(exception $e) {
			throw $e;
		}
	}

	private function runHook($hook, &$target = null)
	{
		try {
			$sinker = array( 'target' => $target );
			$this->hooks->run($hook, $sinker);
			if($target && $sinker['target'][0] != $target[0]) {
				$target = $sinker['target'];
				return false;
			}

		} catch (exception $e) {
			throw new Exception("<strong>Error running hook</strong><br />$e");
		}

		return true;
	}
}
