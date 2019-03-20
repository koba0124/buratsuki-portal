<?php
class Model_Users
{
	const TABLE_NAME = 'users';

	public static function get_list()
	{
		$query = DB::select('username', 'profile_fields')
					->from(self::TABLE_NAME);
		$records = $query->execute()->as_array();
		foreach ($records as &$record) {
			$profile_fields = unserialize($record['profile_fields']);
			foreach ($profile_fields as $field => $value) {
				$record[$field] = $value;
			}
		}
		unset($record);
		return $records;
	}

	public static function get_by_user_id($user_id)
	{
		$query = DB::select('username', 'profile_fields')
					->from(self::TABLE_NAME)
					->where('username', '=', $user_id)
					->limit(1);
		$record = $query->execute()->as_array()[0] ?? null;
		if (! $record) {
			return null;
		}
		$profile_fields = unserialize($record['profile_fields']);
		foreach ($profile_fields as $field => $value) {
			$record[$field] = $value;
		}
		return $record;
	}
}