<?php

function peak_memory_string()
{
	$pmu = memory_get_peak_usage();

	if($pmu > 1*pow(10,6))
		$pmu = $pmu/pow(10,6) . " MB";
	else
	if($pmu > 1000)
		$pmu = $pmu/1000 . " KB";
	else
		$pmu .= " B";

	return $pmu;
}

function time_us_ms($time)
{
	return round((($time)*1000000)/1000);
}
