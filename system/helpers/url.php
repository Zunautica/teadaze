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
