<?php

	class UsersModel extends EntityModel {
		public function loaded($msg)
		{
			echo "Hello $msg";
		}
	}
