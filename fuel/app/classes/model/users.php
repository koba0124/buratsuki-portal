<?php
class Model_Users
{
	const TABLE_NAME = 'users';

	/**
	 * 全ユーザのリストを取得
	 * @return array ユーザレコードの配列
	 */
	public static function get_list()
	{
		$query = DB::select('username', 'profile_fields')
					->from(self::TABLE_NAME);
		$records = $query->execute()->as_array();
		$records = self::append_profile_fields($records);
		return $records;
	}

	/**
	 * user_idに対応するユーザレコードを取得
	 * @param  string $user_id ユーザID
	 * @return array           ユーザレコード
	 */
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

	/**
	 * シリアライズされたprofile_fieldsの値を配列に追加
	 * @param  array $records ユーザデータの入ったレコードの配列
	 * @return array          profile_fieldsの各要素が追加された配列
	 */
	public static function append_profile_fields($records)
	{
		foreach ($records as &$record) {
			$profile_fields = unserialize($record['profile_fields']);
			foreach ($profile_fields as $field => $value) {
				$record[$field] = $value;
			}
		}
		unset($record);
		return $records;
	}
}