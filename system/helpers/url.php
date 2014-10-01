<?php

function url_array($url)
{
	$a = explode('?', $url);
	$a = explode('/', $a[0]);
	$u = array();
	foreach($a as $v)
		if($v != '')
			$u[] = $v;

	return $u;
}

function url_next_dir(array $url)
{
	if(sizeof($url) == 0)
		return null;
	return array_splice($url, 1);
}

function asset_script_url($asset)
{
	return "<script src=\"assets/scripts/$asset\" type=\"text/javascript\"></script>\n";
}

function asset_style_url($asset)
{
	return "<link href=\"assets/styles/$asset\" type=\"text/css\" rel=\"stylesheet\" />\n";
}
