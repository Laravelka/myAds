<?php 

if (!function_exists('isNumber'))
{
	function isNumber($value) {
		return preg_match('/^\d/', $value) === 1;
	}
}
