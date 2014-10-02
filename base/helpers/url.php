<?php
/* Copyright 2014, Zunautica Initiatives Ltd.
*  Distributed under the MIT License (http://opensource.org/licenses/MIT)
*/
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
	return "<script src=\"site/assets/scripts/$asset\" type=\"text/javascript\"></script>\n";
}

function asset_style_url($asset)
{
	return "<link href=\"site/assets/styles/$asset\" type=\"text/css\" rel=\"stylesheet\" />\n";
}
