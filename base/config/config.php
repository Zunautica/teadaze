<?php
/* Copyright 2014, Zunautica Initiatives Ltd.
*  Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

/**
 * The configuration script that is loaded up by entry.
 * 
 * This defines the global config array and the hooks
 * array so they are always available to the framework.
 */
	$config = array(
		'dbuser' => 'limagent',
		'dbpass' => 'SKK9dW6fe3Az9u7Y',
		'db' => 'lim',
		'auto_control' => 'portal',
		'auto_frame' => 'std.clear',
		'dynamic_keyword' => 'ajax',
		'debug' => true
	);

	$hooks = array();
	$hooks['onrequest'] = array();
