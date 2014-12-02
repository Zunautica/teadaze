<?php
/* Copyright 2014, Zunautica Initiatives Ltd.
*  Distributed under the MIT License (http://opensource.org/licenses/MIT)
*/

	include('TableModel.php');

	/**
	 * A model for representing any form of data or business logic
	 *
	 * These represent things like users or interfaces for algorithms.
	 * They can be quickly loaded from ControlType flavoured objects.
	 * They allow controllers to deal with the data.
	 * 
	 * Models come with database access as standard and allow quick
	 * communication with the database through a Table Model. You can
	 * also dereference the table object when calling the method; this
	 * can help for readablity:
	 *
	 * $this->table('users')->select('*');
	 *
	 * This class also acts as the loader for instances of itself
	 * using the static load method.
	 */
	abstract class Model extends DBAccessor implements DBInterface {

		/**
		 * A method for generating a new isntance of a table model
		 *
		 * This can be used to quickly send a table orientated
		 * statement to the database. You can dereference the 
		 * table object for readability.
		 *
		 * @method table(string $table)
		 * @param string $table The name of the table to assign to the Table Model
		 * @access protected
		 * @return PTableModel The newly instantiated Table Model
		 */
		protected function table($table)
		{
			return new PXTableModel($this->db, $table);
		}

		/**
		 * Send a straight MySQL query to the database
		 *
		 * Use this if you just want to send a statement.
		 * It will pass back an associative array or boolean
		 * depending on the statement and result.
		 *
		 * This method is *dangerous* - it does not provide
		 * any sanitation
		 *
		 * @method query(string $sql)
		 * @param string $sql The SQL statement to send
		 * @access public
		 * @return array|boolean The result of the query
		 */
		protected function query($sql)
		{
			return $this->db->query($sql);
		}

		public function setDatabase($database){}
	}
