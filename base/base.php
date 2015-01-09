<?php
/* Copyright 2014, Zunautica Initiatives Ltd.
*  Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

/**
 * The abstract base for many classes which includes useful methods
 *
 * This class is used for many of the classes that share a lot of
 * functionality like accessing request variables or loading up other
 * files from the system
 */
	abstract class PXBase
	{
		/**
		* Used to get a $_POST variable. Leaving $var blank returns $_POST
		*
		* @method _post(string $var)
		* @param string $var The variable to retrieve
		* @access protected
		* @return string|null The value of the variable or null if it is not set
		*/
		final protected function _post($var = null)
		{
			if($var == null)
				return $_POST;

			return isset($_POST[$var]) ? $_POST[$var] : null;
		}

		/**
		* Used to get a $_GET variable. Leaving $var blank returns $_GET
		*
		* @method _get(string $var)
		* @param string $var The variable to retrieve
		* @access protected
		* @return string|null The value of the variable or null if it is not set
		*/
		final protected function _get($var = null)
		{
			if($var == null)
				return $_GET;

			return (isset($_GET[$var])) ? $_GET[$var] : null;
		}

		/**
		* Used to get a $_SESSION variable. Leaving $var blank returns $_SESSION
		*
		* @method _session(string $var)
		* @param string $var The variable to retrieve
		* @access protected
		* @return string|null The value of the variable or null if it is not set
		*/
		final protected function _session($var = null)
		{
			if($var == null)
				return $_SESSION;

			return (isset($_SESSION[$var])) ? $_SESSION[$var] : null;
		}

		/**
		 * Used to set a $_SESSION variable. Setting $var as null unsets the variable
		 *
		 * @method _sessionSet(string $var, mixed $value)
		 * @param string $var The name of the variable to set
		 * @param mixed $value The value to assign to the session variable
		 * @access protected
		 */

		final protected function _sessionSet($var, $value)
		{
			if($value === null)
				unset($_SESSION[$var]);

			$_SESSION[$var] = $value;
		}

		/**
		* Used to inclue up an arbitrarily placed file
		*
		* @method include(string $path)
		* @param string $path The path and name of the file to include
		* @access protected
		* @return boolean The result of the inclusion
		*/
		final protected function includeFile($path)
		{
			return (file_exists($path)) ? include($path) : false;
		}

		/**
		* Used to include a file in the extern directory
		*
		* @method includeExtern(string $path)
		* @param string $path The path and name of the file to include, relative to site/extern/
		* @access protected
		* @return boolean The result of the inclusion
		*/
		final protected function includeExtern($path)
		{
			return (file_exists("site/extern/$path")) ? include("site/extern/$path") : false;
		}
	}

	class CallbackHookable {
		public function setCallback($event, $callback)
		{
			$event = "{$event}CB";
			if(!property_exists($this, $event))
				return;

			$this->$event = $callback;
		}
	}
