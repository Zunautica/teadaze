<?php
/* Copyright 2014, Zunautica Initiatives Ltd.
*  Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */
namespace Teadaze;
/**
 * A plugin class that can be used to define plugin functionality for hooks
 *
 * You used plugins on hooks. A plugin is placed on a hookline
 * and is passed data to be manipulated in some way. The standard 
 * method for a single purpose plugin is run() which has to be 
 * defined regardless of the behaviour of the plugin.
 *
 * The abstract Plugin class also has a static method for loading
 * any sort of plugin. The plugins are separated out by package.
 */
abstract class Plugin extends ControlType
{
	/**
	 * The standard method for running a plugin.
	 *
	 * Any child objects must define this method. It
	 * is called when standard plugin behaviour is run.
	 *
	 * The sinker is a passed-by-reference data type
	 * that is manipulated by the plugin. For example
	 * a plugin that is used as a router is passed in
	 * a framework sinker, which could consist of an
	 * array representation of the target URL - the 
	 * plugin  will alter the array based on it's data 
	 * so it will be pointing to a different target.
	 *
	 * @method run(&$sinker)
	 * @param array $sinker The sinker of data passed down the hookline into plugins
	 * @access public
	 */
	abstract public function run(&$sinker);
}
