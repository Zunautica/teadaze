<?php
/* Copyright 2014, Zunautica Initiatives Ltd.
*  Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */
namespace Teadaze;
/**
 * A Table Model for rapidly sending table orientated statements to the database
 *
 * This can be useful for making quick queries. This is the closest to an ORM
 * this framework will natively use.
 */
class TableModel
{
	/** @var DBO $dbo The database object */
	private $dbo = null;

	/** @var string $table The name of the table this model is representing */
	private $table = null;

	/**
	 * A Table Model is instantiated with a table name and database connection
	 *
	 * @method __construct(DBO $dbo, string $table)
	 * @param DBO $dbo The database object to use
	 * @param string $table The to represent
	 * @access public
	 */
	public function __construct($dbo, $table)
	{
		$this->dbo = $dbo;
		$this->table = $table;
	}

	/**
	* Send an INSERT statement
	*
	* This method takes both string and arrays. You can specify
	* the values section using straight MySQL or an associative 
	* array which uses the the DBO::procInsert.
	*
	* This method may be *dangerous* if a string is used for the
	* values since it will not provide any sanitation
	*
	* @method insert(string|array $values)
	* @param array|string $values The string or associative array of values
	* @access public
	* @return boolean The result of the query
	*/
	public function insert($values)
	{
		if(is_array($values))
			return $this->dbo->procInsert($this->table, $values);

		$sql = "INSERT INTO `{$this->table}` $values";

		return $this->dbo->query($sql);
	}

	/**
	* Send an UDPATE statement
	*
	* This method takes both string and arrays. You can specify
	* the values section using straight MySQL or an associative 
	* array which uses the the DBO::procUpdate..
	*
	* The where section is written as you would in a normal
	* stamement.
	*
	* This method may be *dangerous* if a string is used for the
	* values since it will not provide any sanitation
	*
	* @method update(string|array $values, string $where)
	* @param string|array $values The string or associative array of values
	* @param string $where The where section of the statement
	* @access public
	* @return boolean The result of the query
	*/
	public function update($values, $where = null)
	{
		if(is_array($values)) {
			if($where == null)
				return null;
			else
				return $this->dbo->procUpdate($this->table, $values, $where);
		}

		$sql = "UPDATE `{$this->table}` SET $values WHERE $where";
		return $this->dbo->query($sql);
	}

	/**
	* Send an DELETE statement
	*
	* The where is written as you would a normal statement
	*
	* @method delete($where)
	* @param string $where The where section of the statement
	* @access public
	* @return boolean The result of the query
	*/

	public function delete($where = null)
	{
		return $this->dbo->procDelete($this->table, $where);
	}

	/**
	* Send an SELECT statement
	*
	* The where section is written as you would a normal statement
	* The optional where is written as you would a normal statement
	*
	* @method select($values, $where)
	* @param string|array $values The string or associative array of values
	* @param string $where The where section of the statement
	* @access public
	* @return array|boolean The result of the query as an associative array on success; FALSE on failure
	*/
	public function select($values, $where = null)
	{
		$sql = "SELECT $values FROM `$this->table`";
		if($where)
			$sql .= " WHERE $where";
		return $this->dbo->query($sql);
	}
}
