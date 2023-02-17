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

	public static function _validation_valid_twitter($val)
	{
		if (! $val) {
			return true;
		}
		return preg_match('/^[0-9a-zA-Z_]+$/u', $val) > 0;
	}

	public static function _validation_valid_card_id($val)
	{
		$card_ids = Model_CardsMaster::get_card_ids_list();
		return in_array($val, $card_ids);
	}

	public static function _validation_valid_occupation_id($val)
	{
		$card_ids = Model_CardsMaster::get_card_ids_list('occupation');
		return in_array($val, $card_ids);
	}

	public static function _validation_valid_minor_improvement_id($val)
	{
		$card_ids = Model_CardsMaster::get_card_ids_list('minor_improvement');
		return in_array($val, $card_ids);
	}

	public static function _validation_valid_major_improvement_id($val)
	{
		$card_ids = Model_CardsMaster::get_card_ids_list('major_improvement');
		return in_array($val, $card_ids);
	}

	public static function _validation_valid_other_id($val)
	{
		//その他の場合、なんでもOK
		return _validation_valid_card_id($val);
	}
}