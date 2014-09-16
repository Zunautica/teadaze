<?php

	class RecordModel extends Model implements DBHandler {
		abstract public function update();
		abstract public function insert();
		abstract public function delete();
	}
