<?php
/* Copyright 2014, Zunautica Initiatives Ltd.
*  Distributed under the MIT License (http://opensource.org/licenses/MIT)
*/

include('TableModel.php');
/**
 * An abstract model for managing data from a database
 *
 * These models come with database access and allow quick
 * communication with the database through a Table Model. You can
 * also dereference the table object when calling the method; this
 * can help for readablity:
 *
 * $this->table('users')->select('*');
 */
abstract class DatabaseModel extends Model
implements DBInterface {
	private $db;

	public function setDatabase($db) {
		$this->db = $db;
	}

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
	protected function table($table) {
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
	protected function query($sql) {
		return $this->db->query($sql);
	}
}
