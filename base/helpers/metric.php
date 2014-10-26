<?php
/* Copyright 2014, Zunautica Initiatives Ltd.
*  Distributed under the MIT License (http://opensource.org/licenses/MIT)
*/

/**
 * A function for converting the peak memory usage to a string
 *
 * @method peak_memory_string()
 * @return string The string representation of PMU in the largest unit
 */
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

/**
 * A function for converting microseconds to milliseconds
 *
 * @method time_us_ms($time)
 * @param float $time The microtime
 * @return integer The rounded total of milliseconds
 */
function time_us_ms($time)
{
	return round((($time)*1000000)/1000);
}
