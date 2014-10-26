<?php
function frame_hook($hook, array $hookline)
{
	global $hooks;
	$hooks[$hook] = $hookline;
}

function controller_hook($controller, array $hookline)
{
	global $hooks;
	$hooks["{$controller}Controller"] = $hookline;
}
function cvm_hook($controller, $model, array $hookline)
{
	$vals = explode('.', $controller);
	global $hooks;
	if($vals[1] != '*')
		$vals[1] .= 'View';
	$hooks["{$vals[0]}Controller.{$vals[1]}.{$model}"] = $hookline; 
	
}
