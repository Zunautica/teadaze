<?php

	class UsersModel extends Model {
		public function getUsers()
		{
			return $this->table('users')->select('*');
		}
	}
