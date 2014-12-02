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
		'dbuser' => '',
		'dbpass' => '',
		'db' => '',
		'auto_control' => 'portal',
		'dynamic_keyword' => 'ajax',
		'debug' => true,
		'initialiser' => 'BaseInitialiser',
		'loaders' => array(
			'controller' => 'ControllerLoader',
			'model' => 'ModelLoader',
			'plugin' => 'PluginLoader'
		)
	);

	$hooks = array();
	$hooks['onrequest'] = array();
