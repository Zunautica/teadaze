<?php
include('system/entry.php');
$entry = new Entry();
try {
	echo $entry->run();
} catch(exception $e) {
	echo $e;
}


echo peak_memory_string();
