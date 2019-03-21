<?php
class Helper
{
	public static function validate_class($error_fields, $target_field, $key = null)
	{
		if (! Input::post()) {
			return 'validate';
		}
		if (in_array($target_field, $error_fields)) {
			return 'validate invalid';
		}
		if ($key) {
			if (in_array($target_field . '.' . $key, $error_fields)) {
				return 'validate invalid';
			}
		}
		return 'validate valid';
	}
}