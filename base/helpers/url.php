<?php
/* Copyright 2014, Zunautica Initiatives Ltd.
*  Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */
namespace Teadaze;
/**
 * A function to quickly generate an array of subdirectories from a given URL
 *
 * Use this to quickly convert, say, a URI to an array for use in controllers
 * 
 * For example:
 * 
 * http://sub.domain.top/first/second?foo=bar
 *
 * becomes
 *
 * array( "first", "second")
 *
 * @method url_array(string $url)
 * @param string $url The URL to convert to an array
 * @return array The URL subdirectories encoded as an array
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

/**
 * A function to go to the next subdirectory in a URL array
 *
 * @method url_next_dir(array $url)
 * @param array $url The array representing the subdirectories of the URL
 * @return array The array with respect to the next subdirectory
 */
function url_next_dir(array $url)
{
	if(sizeof($url) == 0)
		return null;
	return array_splice($url, 1);
}

/**
 * A function to quickly encode an HTML <script> element for a given script asset with respect to the framework directory.
 *
 * Use this function to quickly encode a script's path in the HTML element.
 * If it starts with http:// then it is assumed that the asset is seeded.
 *
 * @method asset_script_url(string $asset)
 * @param string $asset The path to the asset, relative to site/assets/scripts
 * @return string A formatted &lt;script&gt; element
 */
function asset_script_url($asset)
{
	if(isset($asset[7]) && substr($asset, 0, 7) == 'http://')
		return "<script src=\"$asset\" type=\"text/javascript\"></script>\n";

	return "<script src=\"/site/assets/scripts/$asset\" type=\"text/javascript\"></script>\n";
}

/**
 * A function to quickly encode an HTML <link> element for given style asset with respect to the framework directory
 *
 * Use this function to quickly encode a styles's path in the HTML element
 * If it starts with http:// then it is assumed that the asset is seeded.
 *
 * @method asset_style_url(string $asset)
 * @param string $asset The path to the asset, relative to site/assets/styles
 * @return string A formatted &lt;link&gt; element
 */
function asset_style_url($asset)
{
	if(isset($asset[7]) && substr($asset, 0, 7) == 'http://')
		return "<link href=\"$asset\" type=\"text/css\" rel=\"stylesheet\" />\n";

	return "<link href=\"/site/assets/styles/$asset\" type=\"text/css\" rel=\"stylesheet\" />\n";
}
