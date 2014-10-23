<?php
/* Copyright 2014, Zunautica Initiatives Ltd.
*  Distributed under the MIT License (http://opensource.org/licenses/MIT)
*/
	abstract class DBAccessor extends PXBase
	{
		protected $db;
		public function __construct($db)
		{
			$this->db = $db;
		}
	}
?>
