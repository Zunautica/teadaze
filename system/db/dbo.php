<?php

class DBO
{
	private $connection = null;
	private static $dbo = null;

	public function connect()
	{
		global $config;
		$this->connection = new mysqli('localhost', $config['dbuser'], $config['dbpass'], $config['db']);
		if($this->connection->connect_errno)
			die("Failed to connection: " . $this->connection->connect_error);
	}

	public function getError()
	{
		$this->connection->errno;
	}

	public function query($sql)
	{
		echo $sql;
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

	public function procInsert($table, array $values)
	{
		$col = $val = "";
		$sz = sizeof($table);
		foreach($values as $c => $v) {
			$col .= "`$c`";
			if(is_object($v))
				$val .= $v;
			else
				$val .= "'".string_prepare_mysql($v)."'";

			if(--$sz > 0) {
				$col .=',';
				$val .=',';
			}
		}

		if(!$this->query("INSERT INTO `$table` ($col) VALUES ($val)"))
			return false;

		return $this->connect->last_id;
	}

	public function procDelete($table, $where)
	{
		return $this->query("DELETE FROM `$table` WHERE $where");
	}

	public function procUpdate($table, array $values, $where)
	{
		$update = "";
		$sz = sizeof($values);
		foreach($values as $c => $v) {
			if($v == null)
				continue;
			if(is_object($v))
				$update .= "`c`={$v->func}";
			else
				$update .= "`$c`='".string_prepare_mysql($v)."'";

			if(--$sz > 0)
				$update .= ",";
		}
		
		return $this->query("UPDATE `$table` SET $update WHERE $where");
	}

	public static function init()
	{
		if(self::$dbo)
			return self::$dbo;

		$dbo = new DBO();
		$dbo->connect();
		self::$dbo = $dbo;
		return $dbo;
	}

	public static function get()
	{
		return self::$dbo;
	}
}
