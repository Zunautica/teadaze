<?php
include('system/init.php');
class Entry
{
	private $db;
	private function init()
	{
		$this->db = new DBO();
		$this->db->connect();
		LoadModel::setDatabase($this->db);
	}

	public function run()
	{
		global $config;

		$this->init();
		$url = url_array($_SERVER['REQUEST_URI']);
		if($url == null)
			$url[0] = $config['auto_control'];

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
		} catch (exception $e) {
			throw new Exception("<strong>Error on run()</strong><br />$e");
			return null;
		}

	}
}
