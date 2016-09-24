<?php
/*
   @author Maulana Satya Adi
   @website programmer-id.net
   
   This function do randomize an array with linear conruent method
   you can import your array at first statement and import your
   z0 data on second statement. This function turn an array that has
   randomize with linear conruent method.
*/

function lcm_random($array_str = array(), $z0 = 0)
{
	$id = 0;
	$output = array();
	$rep_len = ceil(count($array_str)/50);
	$zi = $z0;
	for($i = 0; $i < $rep_len; $i++){
		for($n = 0; $n < 50; $n++){
			$zi = fmod((11*$zi)+7, 50);
			if(($zi+($i*50)) < count($array_str)){
				$output[$id] = $array_str[$zi+($i*50)];
				$id++;
			}
		}
	}
	return $output;
}
