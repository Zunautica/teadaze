<?php
/* Copyright 2014, Zunautica Initiatives Ltd.
*  Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */
namespace Teadaze;
/**
 * An abstract class that will make sure that children always have a database connection
 *
 * This can be used if an object requires database communication. It will be instantiated
 * with the database object as a protected property.
 */
	abstract class DBAccessor extends Base
	{
		/** @var DBO $db This is the database connection for child objects to use */
		protected $db;

		/**
		 * The constructor means that this object will have a database connection
		 *
		 * @method __construct($db)
		 * @param DBO $dbo The DBO object to pass in
		 * @access public
		 */
		public function __construct($db)
		{
			$this->db = $db;
		}
	}
?>
