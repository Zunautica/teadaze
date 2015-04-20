<?php

namespace Teadaze;

function autoload($class) {
	throw new Exception("Unable to load class $class");
}

//\spl_autoload_register('\Teadaze\autoload');
