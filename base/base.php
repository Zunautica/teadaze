<?php
/* Copyright 2014, Zunautica Initiatives Ltd.
*  Distributed under the MIT License (http://opensource.org/licenses/MIT)
*/
	abstract class PXBase
	{
		final protected function _post($var)
		{
			return isset($_POST[$var]) ? $_POST[$var] : null;
		}

		final protected function _get($var)
		{
			return (isset($_GET[$var])) ? $_GET[$var] : null;
		}

		final protected function _session($var)
		{
			return (isset($_SESSION[$var])) ? $_SESSION[$var] : null;
		}

		final protected function includeFile($path)
		{
			return (file_exists($path)) ? include($path) : false;
		}

		final protected function includeExtern($path)
		{
			return (file_exists("site/extern/$path")) ? include("site/extern/$path") : false;
		}
	}
