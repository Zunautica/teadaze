<?php
/* Copyright 2014, Zunautica Initiatives Ltd.
*  Distributed under the MIT License (http://opensource.org/licenses/MIT)
*/
namespace Teadaze;
include('base/init.php');

/**
 * The entry to the framework.
 *
 * This object is called as a front controller. It runs through
 * any framework hooks and sends data to the target controller.
 *
 * The Entry object handles both page generation and dynamic
 * requests to controllers.
 */

class Entry
{
	/** @var DBO $db A DBO object for the framework */
	private $db;

	/** @var array $hooks A list of hook and their hooklines */
	private $hooks;

	private $controllerLoader = null;

	/**
	 * The method for initialising an Entry object
	 *
	 * This method handles the loading the definition
	 * of framework hooks and their hooklines, and also
	 * setting up a new database object
	 *
	 * @method init()
	 * @access public
	 */
	private function init()
	{
		global $hooks;
		global $config;

		if($config['db'] != '') {
			$this->db = DBO::init();

			/* debugging hook - not necessary */
			if(isset($hooks['dbo.onquery']))
			$this->db->setCallback('onquery', $hooks['dbo.onquery']);
		}

		$initialiser = new $config['initialiser']();
		$this->controllerLoader = new $config['loaders']['controller']();
		$pluginLoader = new $config['loaders']['plugin']();
		$initialiser->setControllerLoader($this->controllerLoader);
		$initialiser->setModelLoader( new $config['loaders']['model']());
		$initialiser->setPluginLoader($pluginLoader);
		$this->hooks = new HookLines($hooks, $pluginLoader);
	}

	/**
	 * A method for including a configuration or an array of site/config/ configuration files
	 *
	 * This includes any configuration files that relative to site/config/.
	 * If it is a string then it includes a single configuration, otherwise
	 * if it is an array it will sequentially include each config file
	 *
	 * @method includeConfig($config)
	 * @param string|array $config The name or array of names of the configuration file(s)
	 * @access public
	 */
	public function includeConfig($config)
	{
		if(is_array($config)) {
			foreach($config as $c)
				include("site/config/$c.php");
		} else
			include("site/config/$config.php");
	}

	/**
	 * The method called when the Entry object is to handle a request
	 *
	 * This method runs the 'onrequest' hook and then runs
	 * either a full page static request or a dynamic request depending
	 * on whether the dynamic keyword is set as a URL parameter.
	 *
	 * The method returns the fully parsed document
	 *
	 * @method run()
	 * @access public
	 * @return string The complete document for echoing
	 */
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

	/**
	 * The branch method for full page requests, the controller to load set by $target
	 *
	 * Here the object will run any controller hooks that are configured,
	 * then load and run the controller, get the view and place it in a
	 * frame, finally returning the template generated by the frame
	 *
	 * @method runStatic(array $target)
	 * @param array $target The target array, generated by the URL
	 * @access private
	 * @return string Fully parsed document
	 */
	private function runStatic($target)
	{
		global $config;
		try {
			while(!$this->runHook($target[0]."Controller", $target))
				continue;
			$controller = $this->controllerLoader->load($target[0]);
			$controller = $controller->runInit(url_next_dir($target));

			$view = $controller->getView();
			return $view->loadTemplate();
		}
		catch(exception $e) {
			throw $e;
		}
	}

	/**
	 * The branch method for performing a dynamic request on a controller
	 *
	 * This will load the controller and perform a dynamic request. The result
	 * will be generated from the view directly (as opposed from a frame)
	 * as a fully formatted direct response. (eg. formatted as a JSON response)
	 *
	 * @method runDynamic(array $target)
	 * @param array $target The target array, generated by the URL
	 * @access private
	 * @return string Fully parsed response
	 */
	private function runDynamic($target)
	{
		global $config;
		try {
			$config['debug'] = false;
			while(!$this->runHook($target[0]."Controller", $target))
				continue;
			$controller = $this->controllerLoader->load($target[0]);
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

	/**
	 * The method that is used to run a hookline that is associated with a hook
	 *
	 * This will generate a hookline and sequentially pass the sinker 
	 * into each plugin to modify the data. This is a framework sinker so will
	 * have the target set under the 'target' key.
	 *
	 * If the target has changed from the hookline, it will return false so the 
	 * new controller can be checked for a hook; otherwise it will return true
	 *
	 * @method runHook(string $hook, array &$target)
	 * @param string $hook The hook to check
	 * @param array $target The target array generated by the URL
	 * @access private
	 * @return boolean True if the hookline has passed successfully; false if the target has changed
	 */
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
