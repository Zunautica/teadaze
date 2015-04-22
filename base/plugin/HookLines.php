<?php
/* Copyright 2014, Zunautica Initiatives Ltd.
*  Distributed under the MIT License (http://opensource.org/licenses/MIT)
*/
namespace Teadaze;
/**
 * The hooklines is used by the entry object to handle any framework hooks
 *
 * If there are any framework hooks or controller hooks, this will handle 
 * generating the hookline and passing the data sequentially to each plugin
 * define on the hookline.
 */
class HookLines
{
	/** @var array $lines A list of hooklines used by the system */
	private $lines = array();
	private $pluginLoader = null;

	/**
	 * This object is instantiated with a list of hooks
	 * and hooklines automatically
	 *
	 * @method __construct(array $hooks)
	 * @param array $hooks The array of framework hooks and associated hooklines
	 * @access public
	 */
	public function __construct($hooks, GenericLoader &$pluginLoader)
	{
		$this->pluginLoader = &$pluginLoader;
		foreach($hooks as $hook => $line)
			$this->lines[$hook] = $line;
	}

	/**
	 * Run a the hookline of a particular hook with a specified sinker
	 *
	 * This method will setup a hookline and sequentially
	 * pass the sinker into each plugin
	 *
	 * @method run(string $hook, mixed &$sinker)
	 * @param string $hook The name of the hook to run
	 * @param array $sinker The sinker array to pass into the plugins
	 * @access public
	 */
	public function run($hook, &$sinker)
	{
		if(!isset($this->lines[$hook]))
			return;

		foreach($this->lines[$hook] as $item) {
			if(is_object($item) && ($item instanceof \Closure)) {
				$item($sinker);
			} else {
				$plugin = $this->pluginLoader->load($item);
				$plugin->run($sinker);
			}
		}
	}
}
