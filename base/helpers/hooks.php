<?php

/**
 * A function to quickly add a framework hook
 *
 * This function takes the hook name and a hookline to include
 * The hookline has names of plugins in the format 'package.plugin'.
 *
 * @method frame_hook(string $hook, array $hookline)
 * @param string $hook The name of the hook to add
 * @param array $hookline An array of plugin names on a hookline
 */
function frame_hook($hook, array $hookline)
{
	global $hooks;
	$hooks[$hook] = $hookline;
}

/**
 * A function to quickly setup a hook on a controller
 *
 * This function takes the name of the controller and a hookline to include.
 * The hookline has names of plugins in the format 'package.plugin'.
 *
 * @method controller_hook(string $controller, array $hookline)
 * @param string $controller The name of the controller to add the hook for
 * @param array $hookline An array of plugin names on a hookline
 */

function controller_hook($controller, array $hookline)
{
	global $hooks;
	$hooks["{$controller}Controller"] = $hookline;
}

/**
 * A function for setting up a controller-view-model hook.
 *
 * This function can be used to setup some hooklines for a model
 * when it is called from a particular controller, using a particular view
 *
 * The controller-view is in 'controller.view' format. A wildcard \* can be
 * used to specify that the hook is used for any view that a controller loads:
 *
 * 'controller.\*'
 *
 * The hookline is an associative array of methods hooks. Each method hook has
 * a semi-colon separated hookline of plugins.
 *
 * For example:
 * array('myMethod' => 'package.plugin::methodX;package.plugin2::methodY');
 *
 * Will setup a hook on the method myMethod of the model. It will have a 
 * hookline consisting of plugin1 and plugin2, and the function methodX
 * and methodY will be called respectively for each plugin
 *
 * If you want to set the plugin to parse specific data from a row then you
 * can specify in the method call. For example:
 *
 * package.plugin::methodX(rowA)
 *
 * or comma separated multiple rows:
 *
 * package.plugin::methodY(rowB,rowC)
 *
 * An example of a full call would be:
 *
 * <code>
 * cvm_hook('MyController.mainview', 'MyModels.ModelX', array(
 * 	'modelMethod' => 'MyPlugins.PluginA::modificationMethod(about);MyPlugins.PluginB::formatMethod'
 * ));
 * </code>
 *
 * @method cvm_hook(string $controller, string $model, array $hookline)
 * @param string $controller The name of the controller and view combined in 'controller.view' format. view can also be the * wildcard
 * @param string $model The name of the model in 'package.model' format
 * @param array $hookline An associative array of method hooks and their hooklines
 */
function cvm_hook($controller, $model, array $hookline)
{
	$vals = explode('.', $controller);
	global $hooks;
	if($vals[1] != '*')
		$vals[1] .= 'View';
	$hooks["{$vals[0]}Controller.{$vals[1]}.{$model}"] = $hookline; 
	
}
