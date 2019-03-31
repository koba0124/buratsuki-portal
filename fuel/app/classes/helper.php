<?php
class Helper
{
	public static function validate_class($error_fields, $target_field, $key = null, $option = '')
	{
		$return = 'validate';
		if ($option) {
			$return .= ' ' . $option;
		}
		if (! Input::post()) {
			return $return;
		}
		if (in_array($target_field, $error_fields)) {
			return $return . ' invalid';
		}
		if ($key) {
			if (in_array($target_field . '.' . $key, $error_fields)) {
				return $return . ' invalid';
			}
		}
		return $return . ' valid';
	}
}