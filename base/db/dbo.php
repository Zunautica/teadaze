<?php
/* Copyright 2014, Zunautica Initiatives Ltd.
*  Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */
namespace Teadaze;
/**
 * The singleton MySQL object for handling communication with the database
 *
 * This handles the communication with a DB and also has built in methods 
 * for quickly generating basic queries without much overhead.
 */
class DBO extends CallbackHookable
{
	/** @var mysqli $connection The MySQLi Connection object */
	private $connection = null;

	/** @var DBO $dbo The singleton DBO object */
	private static $dbo = null;

	/** @var [String|Array] The callback for the onquery Event */
	protected $onqueryCB = null;

	/**
	 * Connect to the database using the credentials set in config
	 *
	 * This will pull in the global configuration and use dbuser, dbpass and db
	 * for the username, password and database to select respectively. The
	 * singleton object is then set for later use
	 *
	 * @method connection()
	 * @access public
	 */
	public function connect()
	{
		global $config;
		if($config['db'] == "")
			return;

		$this->connection = new \mysqli('localhost', $config['dbuser'], $config['dbpass'], $config['db']);
		if($this->connection->connect_errno)
			die("Failed to connection: " . $this->connection->connect_error);
	}

	/**
	 * Gets the last error from the connection object
	 *
	 * Use this for checking what error occurred. A feedthrough
	 * to mysqli::errno
	 *
	 * @method getError
	 * @access public
	 */
	public function getError()
	{
		$this->connection->errno;
	}

	/**
	 * Send a query to the database and get the result
	 *
	 * Send an SQL statement to the database and get the result
	 * as an array of associative arrays or a result depending on query.
	 * If there was a problem it will spit out a false.
	 *
	 * This method is *dangerous* by itself, there is not sanitation
	 *
	 * @method query(string $sql)
	 * @param string $sql The SQL statement to send
	 * @access public
	 * @return array|boolean The result of rows as associative arrays or the result of a query; false if there was a problem
	 */
	public function query($sql)
	{
		if($this->onqueryCB) // This is the debugging hook
			call_user_func($this->onqueryCB, $sql);

		$r = $this->connection->query($sql);
		if($r === false || $r === true)
			return $r;

		if(!$r->num_rows) {
			$r->close();
			return false;
		}

		$rows = array();
		while(($t = $r->fetch_assoc()) != NULL)
			$rows[] = $t;
		$r->close();
		return $rows;
	}


	/**
	 * A processed INSERT generator
	 *
	 * Use this to generate and send an INSERT using an associative array. 
	 * Keys are columns and values are the array values. If you set an 
	 * object as value it will treat the value as a function instead of a
	 * string
	 *
	 * array ( 'firstname' => 'foo', 'surname' => 'bar')
	 *
	 * will be
	 *
	 * `firstname`='foo', `surname`='bar'
	 *
	 * This method provides sanitation of string values.
	 *
	 * @method procInsert(string $table, array $values)
	 * @param string $table The name of the table to send to
	 * @param array $values The associative array of values to use
	 * @access public
	 * @return boolean The result of the INSERT query
	 */
	public function procInsert($table, array $values)
	{
		$col = $val = "";
		$sz = sizeof($values);
		foreach($values as $c => $v) {
			$col .= "`$c`";
			if(is_object($v))
				$val .= $v->func;
			else
				$val .= "'".string_prepare_mysql($v)."'";

			if(--$sz > 0) {
				$col .=',';
				$val .=',';
			}
		}

		if(!$this->query("INSERT INTO `$table` ($col) VALUES ($val)"))
			return false;

		return $this->connection->insert_id;
	}

	/**
	 * A processed DELETE generator
	 *
	 * Use this to quickly generate and send a DELETE statement.
	 * Enter the WHERE section as you would with a normal delete
	 *
	 * @method procDelete(string $table, string $where
	 * @param string $table The table to use
	 * @param string $where The WHERE section of the query
	 * @access public
	 * @return boolean The result of the query
	 */
	public function procDelete($table, $where)
	{
		return $this->query("DELETE FROM `$table` WHERE $where");
	}

	/**
	 * A processed UPDATE generator
	 *
	 * Use this to generate and send an UPDATE statement using an
	 * associative array. 
	 *
	 * Keys are columns and values are the array values. If you set an 
	 * object as value it will treat the value as a function instead of a
	 * string.
	 *
	 * The WHERE section is as you would write normally in a statement.
	 *
	 * array ( 'firstname' => 'foo', 'surname' => 'bar')
	 *
	 * will be
	 *
	 * (`firstname`, `surname`) VALUES ('foo', 'bar')
	 *
	 * This method provides sanitation of string values.
	 *
	 * @method procUpdate(string $table, array $values, $where)
	 * @param string $table The name of the table to send to
	 * @param array $values The associative array of values to use
	 * @param string $where The WHERE section of the query
	 * @access public
	 * @return boolean The result of the query
	 */
	public function procUpdate($table, array $values, $where)
	{
		$update = "";
		$sz = sizeof($values);
		foreach($values as $c => $v) {
			if($v == null)
				continue;
			if(is_object($v))
				$update .= "`$c`={$v->func}";
			else
				$update .= "`$c`='".string_prepare_mysql($v)."'";

			if(--$sz > 0)
				$update .= ",";
		}
		
		return $this->query("UPDATE `$table` SET $update WHERE $where");
	}

	/**
	 * The static method to initialise the database connection and set the singleton
	 *
	 * This will return the newly created object or the singleton
	 * object that has already been create previously.
	 *
	 * @method init()
	 * @access public
	 * @return DBO An instance of this class
	 */
	public static function init()
	{
		if(self::$dbo)
			return self::$dbo;

		$dbo = new DBO();
		$dbo->connect();
		self::$dbo = $dbo;
		return $dbo;
	}

	/*
	 * A static method to get the  singleton object
	 *
	 * Probably not necessary to use this method since most of the
	 * framework has access to a DBO object if it needs it
	 * 
	 * @method get()
	 * @access public
	 * @return DBO|null The instance of a DBO; null if it hasn't been initialised
	 */
	public static function get()
	{
		return self::$dbo;
	}
}
