<?php
$stime = microtime();
include('system/entry.php');
$entry = new Entry();
try {
	echo $entry->run();
} catch(exception $e) {
	echo $e;
}

$etime = microtime();
if($config['debug'] == true) {
	echo "<div>".peak_memory_string()."</div>";
	echo "<div>".time_us_ms($etime-$stime)."ms</div>";
}




