<?php

// This is just php's sort function; but sometimes its important to just understand what it does.

function quickSort(array $array)
{
	$length = count($array);

	if ($length <= 1 ) {
		return $array;
	}
	$randInt = mt_rand(0, $length - 1);

	$temp = $array[0];
	$array[0] = $array[$randInt];
	$array[$randInt] = $temp;

	$pivot = $array[0];

	$left = $right = $equal =  [];

	$equal[] = $pivot;

	for ($i = 1; $i < $length; $i++) {
		if ($array[$i] < $pivot) {
			$left[] = $array[$i];
		} elseif ($array[$i] > $pivot) {
			$right[] = $array[$i];
		}
		else {
			$equal[] = $array[$i];
		}
	}

	return array_values(array_merge(quickSort($left), $equal ,quickSort($right)));

}