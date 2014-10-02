<?php
	echo "Ajax-Value:'{$this->values}'\n";
	echo "\nGet:\n";
	foreach($_GET as $k => $v)
		echo "$k => $v\n";
	echo "\nPost:\n";
	foreach($_POST as $k => $v)
		echo "$k => $v\n";
