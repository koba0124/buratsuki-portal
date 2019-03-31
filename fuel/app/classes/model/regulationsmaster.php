<?php
class Model_RegulationsMaster
{
	const TABLE_NAME = 'regulations_master';
	const CACHE_NAME = 'regulations_master';

	public static function get_list()
	{
		try {
			$list = Cache::get(self::CACHE_NAME);
			return $list;
		} catch (CacheNotFoundException $e) {}
		$query = DB::select()
					->from(self::TABLE_NAME);
		$records = $query->execute()->as_array();
		$list = array_column($records, 'regulation_name', 'regulation_type');
		$list = [null => '[未選択]'] + $list;
		Cache::set(self::CACHE_NAME, $list, 3 * 30 * 24 * 60 * 60);
		return $list;
	}
}