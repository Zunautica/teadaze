<?php
/* Copyright 2014, Zunautica Initiatives Ltd.
*  Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */
namespace Teadaze;
/**
 * This defines methods that are useful for any object that may require specific assets
 * in order to function.
 *
 * You set set scripts and styles that need to be included when the child object is used
 * in the browser. You can also merge assets from other AssetHandler type of objects.
 *
 * The assets array is split into 'scripts' and 'styles'
 */
abstract class AssetHandler {

	/** @var array $assets('scripts' => array(), 'styles' => array()) */
	private $assets = array('scripts' => array(), 'styles' => array());

	/**
	 * Add a script to the asset array
	 *
	 * Use this method to include a script in the asset array which
	 * will later be loaded up by the browser once dispatched. The path
	 * is relative to the site/assets/scripts directory
	 *
	 * @method addScript(string $path)
	 * @param string $path The path to the script
	 * @access protected
	 */
	protected final function addScript($path)
	{
		$this->assets['scripts'][] = $path;
	}

	/**
	 * Add a style to the asset array
	 *
	 * Use this method to include a style in the asset array which
	 * will later be loaded up by the browser once dispatched. The path
	 * is relative to the site/assets/styles directory
	 *
	 * @method addStyle(string $path)
	 * @param string $path The path to the style sheet
	 * @access protected
	 */
	protected final function addStyle($path)
	{
		$this->assets['styles'][] = $path;
	}

	/**
	 * Get the current state of the asset array
	 *
	 * You can specify a type of asset by using
	 * 'script' or 'style' or get the whole array 
	 * by leaving the type blank.
	 *
	 * @method getAssets(string $type)
	 * @param string|null $type An optional type of asset; defaults to null
	 * @access public
	 * @return array The whole asset array or specific asset array
	 */
	public final function getAssets($type = null)
	{
		if($type)
			return $this->assets[$type];

		return $this->assets;
	}

	/**
	 * Used to merge other asset arrays into the current array
	 *
	 * This allows AssetHandler to be structured in a tree and
	 * the parent is able to pull in the assets of it's children
	 *
	 * @method mergeAsset(array $merger)
	 * @param array $merget The asset array to merge
	 * @access public
	 */
	public final function mergeAssets($merger)
	{
		$this->assets['scripts'] = array_merge($this->assets['scripts'], $merger['scripts']);
		$this->assets['styles'] = array_merge($this->assets['styles'], $merger['styles']);
	}
}
