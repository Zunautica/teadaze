<?php
namespace Teadaze;
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
		'dbuser' => '',
		'dbpass' => '',
		'db' => '',
		'auto_control' => 'portal',
		'dynamic_keyword' => 'ajax',
		'debug' => true,
		'initialiser' => '\Teadaze\BaseInitialiser',
		'loaders' => array(
			'controller' => '\Teadaze\ControllerLoader',
			'model' => '\Teadaze\ModelLoader',
			'plugin' => '\Teadaze\PluginLoader'
		)
	);

	$hooks = array();
	$hooks['onrequest'] = array();
