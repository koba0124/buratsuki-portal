<?php
class ValidationRule
{
	public static function _validation_exists_user($val)
	{
		$query = DB::select('username')
					->from('users')
					->where('username', '=', $val);
		$result = $query->execute()->as_array();
		return count($result) === 1;
	}

	public static function _validation_array_unique($val)
	{
		$val = (array) $val;
		$array_unique = array_unique($val);
		return count($val) === count ($array_unique);
	}

	public static function _validation_valid_username($val)
	{
		return preg_match('/^[0-9a-zA-Z_]+$/u', $val) > 0;
	}

	public static function _validation_valid_watchword($val)
	{
		return $val === getenv('WATCHWORD');
	}

}