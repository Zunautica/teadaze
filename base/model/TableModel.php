<?php
	class PTableModel
	{
		private $dbo = null;
		private $table = null;
		public function __construct($dbo, $table)
		{
			$this->dbo = $dbo;
			$this->table = $table;
		}

		public function insert($values)
		{
			if(is_array($insert))
				return $this->dbo->procInsert($this->table, $values);

			$sql = "INSERT INTO `{$this->table}` $values";

			return $this->dbo->query($sql);
		}

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

		public function delete($where = null)
		{
			return $this->dbo->procDelete($this->table, $where);
		}

		public function select($values, $where = null)
		{
			$sql = "SELECT $values FROM `$this->table`";
			if($where)
				$sql .= " WHERE $where";
			return $this->dbo->query($sql);
		}
	}
