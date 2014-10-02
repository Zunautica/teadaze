<?php
/* Copyright 2014, Zunautica Initiatives Ltd.
*  Distributed under the MIT License (http://opensource.org/licenses/MIT)
*/
	abstract class AssetHandler {
		private $assets = array('scripts' => array(), 'styles' => array());

		protected final function addScript($path)
		{
			$this->assets['scripts'][] = $path;
		}

		protected final function addStyle($path)
		{
			$this->assets['styles'][] = $path;
		}

		public final function getAssets($type = null)
		{
			if($type)
				return $this->assets[$type];

			return $this->assets;
		}

		protected final function mergeAssets($merger)
		{
			$this->assets['scripts'] = array_merge($this->assets['scripts'], $merger['scripts']);
			$this->assets['styles'] = array_merge($this->assets['styles'], $merger['styles']);
		}
	}
