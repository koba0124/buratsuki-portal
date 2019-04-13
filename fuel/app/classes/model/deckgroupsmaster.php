<?php
class Model_DeckGroupsMaster
{
	const TABLE_NAME = 'deck_groups_master';
	const CACHE_NAME = 'deck_groups_master';

	/**
	 * デッキグループのリストを取得
	 * @return array デッキグループの配列
	 */
	public static function get_list()
	{
		try {
			$list = Cache::get(self::CACHE_NAME);
			return $list;
		} catch (CacheNotFoundException $e) {}
		$query = DB::select()
					->from(self::TABLE_NAME);
		$records = $query->execute()->as_array();
		$list = array_column($records, null, 'deck_group');
		foreach ($list as &$element) {
			$element['deck_group_elements'] = json_decode($element['deck_group_elements'], true);
		}
		Cache::set(self::CACHE_NAME, $list, 3 * 30 * 24 * 60 * 60);
		return $list;
	}
}