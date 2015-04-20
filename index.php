<?php
$stime = microtime();
include('base/entry.php');
$entry = new \Teadaze\Entry();
include('site/entry.php');
try {
	echo $entry->run();
} catch(exception $e) {
	echo $e;
}
$etime = microtime();
if($config['debug']) {
	echo "<div>".Teadaze\peak_memory_string()."</div>";
	echo "<div>".Teadaze\time_us_ms($etime-$stime)."ms</div>";
}




